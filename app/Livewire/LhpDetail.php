<?php

namespace App\Livewire;

use App\Models\Lhp;
use App\Models\Pegawai;
use App\Models\JabatanTim;
use App\Models\TindakLanjut;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class LhpDetail extends Component
{
    use WithFileUploads;

    public Lhp $lhp;
    public $lhpId;

    // Temuan Properties
    public $temuan;
    public $rincian_rekomendasi;
    public $besaran_temuan;

    // Tindak Lanjut Properties
    public $showTindakLanjutModal = false;
    public $tindakLanjutId;
    public $tindakLanjutDescription;
    public TindakLanjut|null $editingTindakLanjut = null;
    public $tindakLanjutFile;

    // NEW: Susunan Tim Properties
    public $newTimPegawaiId = '';
    public $newTimJabatanId = '';

    public function mount($id)
    {
        $this->lhpId = $id;
        // Eager load all necessary relationships
        $this->lhp = Lhp::with(['user', 'tindakLanjuts', 'tim.jabatanTim'])->findOrFail($id);

        $this->temuan = $this->lhp->temuan;
        $this->rincian_rekomendasi = $this->lhp->rincian_rekomendasi;
        $this->besaran_temuan = $this->lhp->besaran_temuan;
    }

    #[On('upload-completed')]
    public function refreshLhpData()
    {
        $this->lhp->refresh();
    }

    public function deleteFile($fieldName)
    {
        if ($this->lhp->$fieldName && Storage::disk('public')->exists($this->lhp->$fieldName)) {
            Storage::disk('public')->delete($this->lhp->$fieldName);
        }
        $this->lhp->$fieldName = null;
        $this->lhp->save();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'File berhasil dihapus.']);
        $this->lhp->refresh();
    }

    public function saveTemuan()
    {
        $this->validate([
            'temuan' => 'nullable|string',
            'rincian_rekomendasi' => 'nullable|string',
            'besaran_temuan' => 'nullable|numeric|min:0',
        ]);
        $this->lhp->update($this->only(['temuan', 'rincian_rekomendasi', 'besaran_temuan']));
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Data temuan berhasil disimpan.']);
    }

    // --- Tindak Lanjut Methods ---
    public function openTindakLanjutModal($id = null)
    {
        $this->resetTindakLanjutForm();
        if ($id) {
            $this->editingTindakLanjut = TindakLanjut::findOrFail($id);
            $this->tindakLanjutId = $this->editingTindakLanjut->id;
            $this->tindakLanjutDescription = $this->editingTindakLanjut->description;
        }
        $this->showTindakLanjutModal = true;
    }

    public function closeTindakLanjutModal()
    {
        $this->showTindakLanjutModal = false;
        $this->resetTindakLanjutForm();
    }

    private function resetTindakLanjutForm()
    {
        $this->reset(['tindakLanjutId', 'tindakLanjutDescription', 'tindakLanjutFile', 'editingTindakLanjut']);
        $this->resetErrorBag();
    }
    
    // This method is now only for edits without a file change
    public function saveTindakLanjut()
    {
        if ($this->tindakLanjutFile) {
            // This case is handled by the custom uploader
            return;
        }

        $this->validate(['tindakLanjutDescription' => 'nullable|string|max:1000']);
        
        if ($this->tindakLanjutId) {
            $tindakLanjut = TindakLanjut::find($this->tindakLanjutId);
            if ($tindakLanjut) {
                $tindakLanjut->description = $this->tindakLanjutDescription;
                $tindakLanjut->save();
                $this->dispatch('notify', ['type' => 'success', 'message' => 'Keterangan berhasil diperbarui.']);
            }
        }
        $this->closeTindakLanjutModal();
        $this->lhp->refresh();
    }

    public function deleteTindakLanjut($id)
    {
        $tindakLanjut = TindakLanjut::findOrFail($id);
        if ($tindakLanjut->file_path) {
            Storage::disk('public')->delete($tindakLanjut->file_path);
        }
        $tindakLanjut->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Tindak Lanjut berhasil dihapus.']);
        $this->lhp->refresh();
    }

    // --- NEW: Susunan Tim Methods ---
    public function addTim()
    {
        $this->validate([
            'newTimPegawaiId' => 'required|exists:pegawais,id',
            'newTimJabatanId' => 'required|exists:jabatan_tims,id',
        ]);

        // Prevent adding the same person twice
        if ($this->lhp->tim()->where('pegawai_id', $this->newTimPegawaiId)->exists()) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Pegawai sudah ada dalam tim.']);
            return;
        }

        // Create new PerintahTugas record
        $this->lhp->tim()->create([
            'pegawai_id' => $this->newTimPegawaiId,
            'jabatan_tim_id' => $this->newTimJabatanId
        ]);
        
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Anggota tim berhasil ditambahkan.']);
        $this->reset(['newTimPegawaiId', 'newTimJabatanId']);
        $this->lhp->refresh();
    }

    public function removeTim($pegawaiId)
    {
        $this->lhp->tim()->where('pegawai_id', $pegawaiId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Anggota tim berhasil dihapus.']);
        $this->lhp->refresh();
    }

    public function render()
    {
        // Fetch data for the dropdowns in the "Susunan Tim" tab
        $currentTimIds = $this->lhp->tim->pluck('id');
        $pegawaiOptions = Pegawai::whereNotIn('id', $currentTimIds)->orderBy('nama')->get();
        $jabatanTimOptions = JabatanTim::orderBy('nama')->get();
        
        return view('livewire.lhp-detail', [
            'pegawaiOptions' => $pegawaiOptions,
            'jabatanTimOptions' => $jabatanTimOptions,
        ])->layout('components.layouts.app', ['title' => 'Detail LHP: ' . $this->lhp->nomor_lhp]);
    }
}
