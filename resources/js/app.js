import './bootstrap';

/**
 * ── Konfirmasi hapus / batal ──────────────────────────────────
 * Semua tombol dengan data-confirm="pesan" akan meminta konfirmasi
 * sebelum melanjutkan submit form-nya.
 */
document.addEventListener('DOMContentLoaded', () => {

    // Konfirmasi sebelum submit form berbahaya
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function (e) {
            const pesan = this.dataset.confirm || 'Apakah Anda yakin?';
            if (!confirm(pesan)) {
                e.preventDefault();
            }
        });
    });

    // Auto-dismiss alert setelah 4 detik
    document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });

    // Kalkulasi total harga reservasi (form create/edit)
    const tglCheckin  = document.getElementById('tanggal_checkin');
    const tglCheckout = document.getElementById('tanggal_checkout');
    const selectKamar = document.getElementById('kamar_id');
    const panelKalkulasi = document.getElementById('panel-kalkulasi');

    function hitungTotal() {
        if (!tglCheckin || !tglCheckout || !selectKamar) return;

        const ci  = tglCheckin.value;
        const co  = tglCheckout.value;
        const opt = selectKamar.selectedOptions[0];

        if (!ci || !co || !opt?.dataset.harga) {
            if (panelKalkulasi) panelKalkulasi.classList.add('hidden');
            return;
        }

        const malam = Math.round((new Date(co) - new Date(ci)) / 86_400_000);
        if (malam <= 0) {
            if (panelKalkulasi) panelKalkulasi.classList.add('hidden');
            return;
        }

        const harga = parseFloat(opt.dataset.harga);
        const total = malam * harga;
        const fmt   = n => 'Rp ' + n.toLocaleString('id-ID');

        document.getElementById('info-malam') && (document.getElementById('info-malam').textContent = malam);
        document.getElementById('info-harga') && (document.getElementById('info-harga').textContent = fmt(harga));
        document.getElementById('info-total') && (document.getElementById('info-total').textContent = fmt(total));

        if (panelKalkulasi) panelKalkulasi.classList.remove('hidden');
    }

    tglCheckin  && tglCheckin.addEventListener('change', hitungTotal);
    tglCheckout && tglCheckout.addEventListener('change', hitungTotal);
    selectKamar && selectKamar.addEventListener('change', hitungTotal);

    // Preview foto upload
    const inputFoto = document.getElementById('input-foto');
    const previewFoto = document.getElementById('preview-foto');
    if (inputFoto && previewFoto) {
        inputFoto.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    previewFoto.src = e.target.result;
                    previewFoto.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
