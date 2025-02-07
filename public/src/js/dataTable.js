
    $(document).ready(function() {
        // Initialisation de DataTable
        $('table').DataTable({
            "paging": true,        // Activer la pagination
            "searching": true,     // Activer la recherche
            "ordering": true,      // Activer le tri
            "info": true           // Afficher les informations (Page 1 sur 10)
        });
    });
