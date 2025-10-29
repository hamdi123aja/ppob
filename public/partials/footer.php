<footer>
    <p>&copy; <?= date('Y') ?> PPOB App | All Rights Reserved</p>
</footer>
</body>

</html>
<script>
$(document).ready(function () {
    $('.datatable').DataTable({
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
        order: [[0, 'asc']], // default urut kolom pertama (No)
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya"
            },
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data tidak ditemukan"
        },
        columnDefs: [
            { orderable: false, targets: -1 } // kolom terakhir (Aksi) tidak bisa diurutkan
        ],
        dom:
            "<'row mb-3'<'col-sm-6'l><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-3'<'col-sm-6'i><'col-sm-6'p>>"
    });
});
</script>
