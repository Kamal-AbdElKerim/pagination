<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
      .pagination-wrapper {
          display: flex;
          justify-content: space-between;
          align-items: center;
          width: 100%;
          margin-top: 20px;
      }

      .pagination {
          display: flex;
          list-style: none;
          padding: 0;
      }

      .pagination .page-item {
          margin: 0 5px;
      }

      .pagination .page-link {
          padding: 8px 12px;
          border-radius: 8px;
          border: 1px solid #8be48b;
          color: #333;
          transition: background 0.3s ease;
      }

      .pagination .page-link:hover,
      .pagination .active .page-link {
          background-color: #8be48b;
          color: white;
          border-color: #8be48b;
      }

      .pagination .disabled .page-link {
          pointer-events: none;
          opacity: 0.5;
      }


        .results-info {
            font-size: 16px;
            color: #666;
        }

        .table {

            margin:  auto;
        }

     .flex {
         -webkit-box-flex: 1;
         -ms-flex: 1 1 auto;
         flex: 1 1 auto
     }

     @media (max-width:991.98px) {
         .padding {
             padding: 1.5rem
         }
     }

     @media (max-width:767.98px) {
         .padding {
             padding: 1rem
         }
     }

     .padding {
         padding: 5rem
     }

     .card {
         box-shadow: none;
         -webkit-box-shadow: none;
         -moz-box-shadow: none;
         -ms-box-shadow: none
     }



     .border-secondary,
     .loader-demo-box {
         border-color: #a3a4a5 !important
     }

     .rounded,
     .loader-demo-box {
         border-radius: 0.25rem !important
     }

     .loader-demo-box {
         width: 100%;
         height: 200px
     }

     .jumping-dots-loader {
         width: 100px;
         border-radius: 100%;
         position: relative;
         margin: 0 auto
     }

     .jumping-dots-loader span {
        display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 100%;
            background-color: rgba(241, 83, 110, 0.8);
            margin: 15px 2px;
     }

     .jumping-dots-loader span:nth-child(1) {
         animation: bounce 1s ease-in-out infinite
     }

     .jumping-dots-loader span:nth-child(2) {
         animation: bounce 1s ease-in-out 0.33s infinite
     }

     .jumping-dots-loader span:nth-child(3) {
         animation: bounce 1s ease-in-out 0.66s infinite
     }

     @keyframes bounce {

         0%,
         75%,
         100% {
             -webkit-transform: translateY(0);
             -ms-transform: translateY(0);
             -o-transform: translateY(0);
             transform: translateY(0)
         }

         25% {
             -webkit-transform: translateY(-20px);
             -ms-transform: translateY(-20px);
             -o-transform: translateY(-20px);
             transform: translateY(-20px)
         }
     }

 /* Skeleton loader styles */
 .skeleton-box {
     width: 100%;
     height: 16px;
     background: linear-gradient(90deg, #e0e0e0 25%, #f5f5f5 50%, #e0e0e0 75%);
     background-size: 200% 100%;
     animation: loading 1.5s infinite;
     border-radius: 4px;
 }

 @keyframes loading {
     0% {
         background-position: 200% 0;
     }
     100% {
         background-position: -200% 0;
     }
 }

 .skeleton td {
     padding: 12px;
 }


    </style>
</head>
<body class="bg-light">

    <div class="container mt-4">
        <h1 class="text-center">Users List</h1>



        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-3">
            <!-- Select per page -->
            <div class="d-flex align-items-center gap-2">
                <label for="perPage" class="fw-semibold text-muted">Show per page:</label>
                <select id="perPage" class="form-select w-auto rounded-pill shadow-sm border-secondary " onchange="fetchUsers()">
                    <option value="4">4</option>
                    <option value="8">8</option>
                    <option value="12">12</option>
                    <option value="16">16</option>
                </select>
            </div>

            <!-- Search input -->
            <div class="position-relative w-100" style="max-width: 300px;">
                <input type="text" id="search" class="form-control rounded-pill ps-4 pe-5 shadow-sm"
                       placeholder="Search by name or email" onkeyup="fetchUsers()">
                <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
            </div>
        </div>

        <!-- Loading Spinner -->




                                <div class="jumping-dots-loader " id="loading"> <span></span> <span></span> <span></span> </div>




        <!-- Users Table -->
        <table class="table table-bordered table-hover bg-white shadow-sm">
            <thead class="table-success">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
           <tbody id="users-list">

           </tbody>

        </table>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            <div id="results-info" class="results-info"></div>
            <ul class="pagination pagination-sm" id="pagination-links"></ul>
        </div>
    </div>

    <script>
        let totalPages = 10;

       function fetchUsers(page = 1) {
           let usersList = document.getElementById('users-list');
           let perPage = document.getElementById('perPage').value; // Get selected per page value
           let search = document.getElementById('search').value;

           // Generate skeleton rows dynamically
           let skeletonHTML = '';
           for (let i = 0; i < perPage; i++) {
               skeletonHTML += `
                   <tr class="skeleton">
                       <td><div class="skeleton-box"></div></td>
                       <td><div class="skeleton-box"></div></td>
                       <td><div class="skeleton-box"></div></td>
                   </tr>
               `;
           }

           usersList.innerHTML = skeletonHTML; // Insert skeleton rows
           document.getElementById('loading').style.visibility = "visible";

           axios.get('/users', { params: { search, page, per_page: perPage } })
               .then(response => {
                   setTimeout(() => {
                       usersList.innerHTML = ''; // Clear skeleton rows

                       response.data.data.forEach(user => {
                           usersList.innerHTML += `<tr>
                               <td>${user.name}</td>
                               <td>${user.email}</td>
                               <td><a href="javascript:void(0);" class="btn btn-sm btn-outline-primary">Edit</a></td>
                           </tr>`;
                       });

                       updateResultsInfo(response.data.pagination);
                       renderPagination(response.data.pagination);
                       document.getElementById('loading').style.visibility = "hidden";
                   }, 500);
               })
               .catch(error => console.error('Error fetching data:', error));
       }


       function renderPagination(pagination) {
           const paginationWrapper = document.getElementById('pagination-links');
           paginationWrapper.innerHTML = '';

           let pageRange = 5;
           let startPage = Math.max(pagination.current_page - Math.floor(pageRange / 2), 1);
           let endPage = Math.min(pagination.current_page + Math.floor(pageRange / 2), pagination.last_page);

           let html = `<ul class="pagination">`;

           // Previous Button
           html += `<li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                       <a href="javascript:void(0);" class="page-link" onclick="${pagination.current_page > 1 ? `fetchUsers(${pagination.current_page - 1})` : ''}">&laquo;</a>
                    </li>`;

           // First Page + Ellipsis
           if (startPage > 1) {
               html += `<li class="page-item"><a href="javascript:void(0);" class="page-link" onclick="fetchUsers(1)">1</a></li>`;
               if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
           }

           // Main Page Numbers
           for (let i = startPage; i <= endPage; i++) {
               html += `<li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                           <a href="javascript:void(0);" class="page-link" onclick="fetchUsers(${i})">${i}</a>
                        </li>`;
           }

           // Last Page + Ellipsis
           if (endPage < pagination.last_page) {
               if (endPage < pagination.last_page - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
               html += `<li class="page-item"><a href="javascript:void(0);" class="page-link" onclick="fetchUsers(${pagination.last_page})">${pagination.last_page}</a></li>`;
           }

           // Next Button
           html += `<li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                       <a href="javascript:void(0);" class="page-link" onclick="${pagination.current_page < pagination.last_page ? `fetchUsers(${pagination.current_page + 1})` : ''}">&raquo;</a>
                    </li>`;

           html += `</ul>`;
           paginationWrapper.innerHTML = html;
       }

        function updateResultsInfo(pagination) {
            let resultsInfo = document.getElementById('results-info');
            let start = (pagination.current_page - 1) * pagination.per_page + 1;
            let end = Math.min(pagination.current_page * pagination.per_page, pagination.total);
            resultsInfo.innerHTML = `Showing ${start}-${end} of ${pagination.total} users`;
        }

        window.onload = fetchUsers;
    </script>

</body>
</html>
