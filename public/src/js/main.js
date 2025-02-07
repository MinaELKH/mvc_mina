function confirmAction(action, teacherId) {
    Swal.fire({
        title: `Êtes-vous sûr de vouloir ${action} cet enseignant ?`,
        text: "Cette action peut être réversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, continuer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the form or make AJAX request
            document.querySelector(`form[data-teacher-id="${teacherId}"]`).submit();
        }
    });
}