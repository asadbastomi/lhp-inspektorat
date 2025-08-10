<?php

namespace App\Livewire;

use App\Models\Lhp;
use App\Models\TindakLanjut;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class LhpDetail extends Component
{
    use WithFileUploads;

    public Lhp $lhp;
    public $lhpId;
    public $temuan;
    public $rincian_rekomendasi;
    public $besaran_temuan;
    public $showTindakLanjutModal = false;
    public $tindakLanjutId;
    public $tindakLanjutDescription;
    public TindakLanjut|null $editingTindakLanjut = null;
    public $tindakLanjutFile;

    public function mount($id)
    {
        $this->lhpId = $id;
        $this->lhp = Lhp::with(['user', 'tindakLanjuts'])->findOrFail($id);
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

    public function saveTindakLanjut()
    {
        $this->validate([
            'tindakLanjutFile' => $this->tindakLanjutId ? 'nullable|file|max:20480' : 'required|file|max:20480',
            'tindakLanjutDescription' => 'nullable|string|max:1000',
        ]);
        
        $data = ['lhp_id' => $this->lhpId, 'description' => $this->tindakLanjutDescription];
        if ($this->tindakLanjutFile) {
            if ($this->tindakLanjutId && $this->editingTindakLanjut?->file_path) {
                Storage::disk('public')->delete($this->editingTindakLanjut->file_path);
            }
            $data['file_path'] = $this->tindakLanjutFile->store('tindak-lanjut', 'public');
            $data['file_name'] = $this->tindakLanjutFile->getClientOriginalName();
        }
            
        TindakLanjut::updateOrCreate(['id' => $this->tindakLanjutId], $data);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Tindak Lanjut berhasil disimpan.']);
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

    public function render()
    {
        return view('livewire.lhp-detail')
            ->layout('components.layouts.app', ['title' => 'Detail LHP: ' . $this->lhp->nomor_lhp]);
    }
}