 new DataTable('#tableList', {
        pageLength: 500, 
        lengthMenu: [
            [100, 200, 500, 1000, 1500, 3000, 5000, 10000, 40000],
            [100, 200, 500, 1000, 1500, 3000, 5000, 10000, 40000]
        ],
        ordering: true,
        searching: true,
        responsive: true
    });
new DataTable('#tableList-1',{ pageLength:50 });
new DataTable('#tableList-2',{ pageLength:50 });