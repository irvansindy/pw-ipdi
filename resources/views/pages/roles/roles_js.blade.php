<script>
    new DataTable('#role_managament', {
        // pagingType: 'first_last_numbers'
    });
    
    new DataTable('#permission_managament', {
        pagingType: 'first_last_numbers'
    });
    
    $('#create_role_user').click(function(e) {
        e.preventDefault()
        // alert('success')
        let create_role_name = $('#create_role_name').val()
        let create_permission_role = $('#create_permission_role').val()

        if (create_role_name == '' || create_role_name == null) {
            alert('Role tidak boleh kosong')
            return false;
        }

        let data = {
            'name': create_role_name,
        }

        // CreateOrUpdateOrDelete('/roles/store', 'post', data)
        CreateOrUpdateOrDelete('role-store', 'post', data, '#formCreateRole')
    })
</script>