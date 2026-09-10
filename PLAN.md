# Plan for Revising /inputdatauser Form

## Files to Modify

1. `app/Http/Controllers/User/InputDataUserController.php`
   - Update `create()` method to fetch dropdown data (OPD, jenis, merk) from MasterData with fallbacks (same as admin)
   - Update `store()` method with:
     * Conditional validation: require `jenis_lainnya` when `jenis_perangkat` is 'lainnya', require `merk_lainnya` when `merk_perangkat` is 'lainnya'
     * Determine final values for jenis and merk (use manual input if 'lainnya' selected)
     * Determine `status_kepemilikan` based on selected OPD: 'Kominfo' if OPD value is exactly 'Kominfo', else 'Colocation'
     * Save `tanggal_input`
     * Generate `kode_perangkat` using `Server::generateKodePerangkat()` with the determined status_kepemilikan and tanggal_input
     * Save other fields (nama_pengirim, nama_penerima, etc.)

2. `resources/views/user/inputdatauser.blade.php`
   - Add readonly Kode Perangkat field at top with helper text below: "Kode perangkat akan dibuat otomatis setelah submit berdasarkan tanggal input dan pilihan OPD."
   - Replace Nama Dinas/OPD text input with a dropdown (copy-paste admin's OPD dropdown from `inputdata.blade.php`, adapted):
     * Field name: `opd`, id: `opd`
     * Label: "Nama Dinas/OPD"
     * Use same MasterData source (`pemilik_perangkat`) with fallback to static OPD array
     * Remove helper text about Colocation condition (not needed)
   - Replace Merk Perangkat text input with a dropdown:
     * Field name: `merk_perangkat`, id: `merk_perangkat`
     * Label: "Merk Perangkat"
     * Use MasterData source (`merk_perangkat`), standard-select (no searchable)
     * Add "Lainnya" option at end
   - Update Jenis Perangkat dropdown:
     * Field name: `jenis_perangkat`, id: `jenis_perangkat`
     * Label: "Jenis Perangkat"
     * Use MasterData source (`jenis_perangkat`) with fallback to hardcoded ['router'=>'Router','switch'=>'Switch','server'=>'Server']
     * Add "Lainnya" option at end
   - Add conditional text inputs:
     * For Jenis: `<input type="text" id="jenis_lainnya" name="jenis_lainnya" class="hidden" ...>` shown when `jenis_perangkat` value is 'lainnya'
     * For Merk: `<input type="text" id="merk_lainnya" name="merk_lainnya" class="hidden" ...>` shown when `merk_perangkat` value is 'lainnya'
   - Add Tanggal Input field:
     * Field name: `tanggal_input`, id: `tanggal_input`
     * Label: "Tanggal Input"
     * Use `<input type="date" ...>` with same styling as admin's standard input
   - Remove Lokasi Rack field (set to null in controller)
   - Add JavaScript to show/hide conditional inputs when dropdown changes
   - Remove format helper text next to Kode Perangkat (already not present, but ensure no format text appears)

## Assumptions

1. The `nomor_rack` column in `servers` table is nullable (admin can fill it later). If not nullable, we will set an empty string or a default value (to be verified).
2. The OPD value 'Kominfo' exists in the master data or static fallback (case-sensitive match).
3. The MasterData table has active entries for categories 'pemilik_perangkat', 'jenis_perangkat', 'merk_perangkat'. If empty, fallbacks will be used.
4. The `Server::generateKodePerangkat()` method is available and correctly implements the KO/CO + YYMMDD + sequence logic.
5. The `Server::hitungStatusKelengkapan()` method works with the data array we provide.

## Verification Steps After Implementation

1. Visit `/inputdatauser` and verify:
   - Kode Perangkat field is readonly at top with helper text below
   - Nama Dinas/OPD is a dropdown with options (same as admin)
   - Merk Perangkat is a dropdown with options (from MasterData) plus "Lainnya"
   - Jenis Perangkat is a dropdown with options (from MasterData or fallback) plus "Lainnya"
   - Selecting "Lainnya" in either dropdown shows a corresponding text input
   - Tanggal Input is a date picker (type="date")
   - Submit button works and redirects to dashboard with success message
2. Check that submitted data appears in `/dashboarduser` (user dashboard) with correct columns
3. Verify that the same data appears in admin's `/manajemen-server` (Perangkat & Server) with status PENDING
4. Confirm that `kode_perangkat` is generated and follows the format (KO/CO + YYMMDD + sequence)
5. Confirm that `status_kepemilikan` is set correctly based on OPD selection
6. Confirm that no admin files were modified