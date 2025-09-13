<?php

namespace App\Livewire;

use App\Models\Lhp;
use App\Models\Pegawai;
use App\Models\JabatanTim;
use App\Models\Temuan;
use App\Models\Rekomendasi;
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

    public $file_p2hp;

    // Temuan Properties
    public $isTemuanModalOpen = false;
    public $temuanId;
    public $jenis_pengawasan;
    public $rincian;
    public $penyebab;

    // Rekomendasi Properties
    public $isRekomendasiModalOpen = false;
    public $rekomendasiId;
    public $currentTemuanId;
    public $rincian_rekomendasi;
    public $besaran_temuan;

    // Tindak Lanjut Properties
    public $isTindakLanjutModalOpen = false;
    public $tindakLanjutId;
    public $currentRekomendasiId;
    public $tindakLanjutDescription;
    public $tindakLanjutFile;

    // File Preview Modal
    public $isFilePreviewModalOpen = false;
    public $previewFileUrl;
    public $previewFileType;

    public $selectedTemuanId = null;

    // Susunan Tim Properties
    public $newTimPegawaiId = '';
    public $newTimJabatanId = '';

    public function mount($id)
    {
        $this->lhpId = $id;
        // Eager load all necessary relationships
        $this->lhp = Lhp::with(['user', 'tim.jabatanTim', 'temuans.rekomendasis.tindakLanjuts'])->findOrFail($id);
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
        $this->dispatch('notify', type: 'success', message: 'File berhasil dihapus.');
        $this->lhp->refresh();
    }

    // --- Temuan Methods ---
    public function openTemuanModal($id = null)
    {
        $this->resetTemuanForm();
        if ($id) {
            $temuan = Temuan::findOrFail($id);
            $this->temuanId = $temuan->id;
            $this->jenis_pengawasan = $temuan->jenis_pengawasan;
            $this->rincian = $temuan->rincian;
            $this->penyebab = $temuan->penyebab;
        }
        $this->isTemuanModalOpen = true;
    }

    public function closeTemuanModal()
    {
        $this->isTemuanModalOpen = false;
        $this->resetTemuanForm();
    }

    private function resetTemuanForm()
    {
        $this->reset(['temuanId', 'jenis_pengawasan', 'rincian', 'penyebab']);
        $this->resetErrorBag();
    }

    public function saveTemuan()
    {
        $this->validate([
            'jenis_pengawasan' => 'required|string|in:' . implode(',', Temuan::$jenisPengawasanOptions),
            'rincian' => 'required|string',
            'penyebab' => 'nullable|string',
        ]);

        Temuan::updateOrCreate(
            ['id' => $this->temuanId],
            [
                'lhp_id' => $this->lhp->id,
                'jenis_pengawasan' => $this->jenis_pengawasan,
                'rincian' => $this->rincian,
                'penyebab' => $this->penyebab,
            ]
        );

        $this->dispatch('notify', type: 'success', message: 'Data temuan berhasil disimpan.');
        $this->closeTemuanModal();
        $this->lhp->refresh();
    }

    public function deleteTemuan($id)
    {
        Temuan::findOrFail($id)->delete();
        $this->dispatch('notify', type: 'success', message: 'Data temuan berhasil dihapus.');
        $this->lhp->refresh();
    }

    // --- Rekomendasi Methods ---
    public function openRekomendasiModal($temuanId, $rekomendasiId = null)
    {
        $this->resetRekomendasiForm();
        $this->currentTemuanId = $temuanId;
        if ($rekomendasiId) {
            $rekomendasi = Rekomendasi::findOrFail($rekomendasiId);
            $this->rekomendasiId = $rekomendasi->id;
            $this->rincian_rekomendasi = $rekomendasi->rincian;
            $this->besaran_temuan = $rekomendasi->besaran_temuan;
        }
        $this->isRekomendasiModalOpen = true;
    }

    public function closeRekomendasiModal()
    {
        $this->isRekomendasiModalOpen = false;
        $this->resetRekomendasiForm();
    }

    private function resetRekomendasiForm()
    {
        $this->reset(['rekomendasiId', 'currentTemuanId', 'rincian_rekomendasi', 'besaran_temuan']);
        $this->resetErrorBag();
    }

    public function saveRekomendasi()
    {
        $this->validate([
            'rincian_rekomendasi' => 'required|string',
            'besaran_temuan' => 'nullable|numeric|min:0',
        ]);

        Rekomendasi::updateOrCreate(
            ['id' => $this->rekomendasiId],
            [
                'temuan_id' => $this->currentTemuanId,
                'rincian' => $this->rincian_rekomendasi,
                'besaran_temuan' => $this->besaran_temuan ?: null,
            ]
        );

        $this->dispatch('notify', type: 'success', message: 'Data rekomendasi berhasil disimpan.');
        $this->closeRekomendasiModal();
        $this->lhp->refresh();
    }

    public function deleteRekomendasi($id)
    {
        Rekomendasi::findOrFail($id)->delete();
        $this->dispatch('notify', type: 'success', message: 'Data rekomendasi berhasil dihapus.');
        $this->lhp->refresh();
    }

    // --- Tindak Lanjut Methods ---
    public function openTindakLanjutModal($rekomendasiId, $tindakLanjutId = null)
    {
        $this->resetTindakLanjutForm();
        $this->currentRekomendasiId = $rekomendasiId;
        if ($tindakLanjutId) {
            $tindakLanjut = TindakLanjut::findOrFail($tindakLanjutId);
            $this->tindakLanjutId = $tindakLanjut->id;
            $this->tindakLanjutDescription = $tindakLanjut->description;
        }
        $this->isTindakLanjutModalOpen = true;
    }

    public function closeTindakLanjutModal()
    {
        $this->isTindakLanjutModalOpen = false;
        $this->resetTindakLanjutForm();
    }

    private function resetTindakLanjutForm()
    {
        $this->reset(['tindakLanjutId', 'currentRekomendasiId', 'tindakLanjutDescription', 'tindakLanjutFile']);
        $this->resetErrorBag();
    }

    public function saveTindakLanjut()
    {
        // This method is now only for edits without a file change
        if ($this->tindakLanjutFile) {
            // File uploads are handled by the custom uploader
            return;
        }

        $this->validate(['tindakLanjutDescription' => 'nullable|string|max:1000']);

        if ($this->tindakLanjutId) {
            $tindakLanjut = TindakLanjut::find($this->tindakLanjutId);
            if ($tindakLanjut) {
                $tindakLanjut->description = $this->tindakLanjutDescription;
                $tindakLanjut->save();
                $this->dispatch('notify', type: 'success', message: 'Keterangan berhasil diperbarui.');
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
        $this->dispatch('notify', type: 'success', message: 'Tindak Lanjut berhasil dihapus.');
        $this->lhp->refresh();
    }

    // --- File Preview Methods ---
    public function openFilePreviewModal($url)
    {
        $this->previewFileUrl = $url;
        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'])) {
            $this->previewFileType = 'image';
        } elseif ($extension === 'pdf') {
            $this->previewFileType = 'pdf';
        } else {
            $this->previewFileType = 'other';
        }

        $this->isFilePreviewModalOpen = true;
    }

    public function closeFilePreviewModal()
    {
        $this->isFilePreviewModalOpen = false;
        $this->previewFileUrl = null;
        $this->previewFileType = null;
    }

    public function filterByTemuan($temuanId)
    {
        if ($this->selectedTemuanId === $temuanId) {
            $this->selectedTemuanId = null; // Allow toggling off the filter
        } else {
            $this->selectedTemuanId = $temuanId;
        }
    }

    // --- Susunan Tim Methods ---
    public function addTim()
    {
        $this->validate([
            'newTimPegawaiId' => 'required|exists:pegawais,id',
            'newTimJabatanId' => 'required|exists:jabatan_tims,id',
        ]);

        // Prevent adding the same person twice
        if ($this->lhp->tim()->where('pegawai_id', $this->newTimPegawaiId)->exists()) {
            $this->dispatch('notify', type: 'error', message: 'Pegawai sudah ada dalam tim.');
            return;
        }

        // Create new PerintahTugas record
        $this->lhp->tim()->create([
            'pegawai_id' => $this->newTimPegawaiId,
            'jabatan_tim_id' => $this->newTimJabatanId
        ]);

        $this->dispatch('notify', type: 'success', message: 'Anggota tim berhasil ditambahkan.');
        $this->reset(['newTimPegawaiId', 'newTimJabatanId']);
        $this->lhp->refresh();
    }

    public function removeTim($perintahTugasId)
    {
        $this->lhp->tim()->where('id', $perintahTugasId)->delete();
        $this->dispatch('notify', type: 'success', message: 'Anggota tim berhasil dihapus.');
        $this->lhp->refresh();
    }

    public function render()
    {
        // Fetch data for the dropdowns in the "Susunan Tim" tab
        $currentTimIds = $this->lhp->tim->pluck('id');
        $pegawaiOptions = Pegawai::whereNotIn('id', $currentTimIds)->orderBy('nama')->get();
        $jabatanTimOptions = JabatanTim::orderBy('nama')->get();

        $temuans = $this->lhp->temuans();
        if ($this->selectedTemuanId) {
            $temuans->where('id', $this->selectedTemuanId);
        }

        return view('livewire.lhp-detail', [
            'pegawaiOptions' => $pegawaiOptions,
            'jabatanTimOptions' => $jabatanTimOptions,
            'temuans' => $temuans->get(),
        ])->layout('components.layouts.app', ['title' => 'Detail LHP: ' . $this->lhp->nomor_lhp]);
    }
}
