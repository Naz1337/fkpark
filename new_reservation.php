<?php

require_once 'layout_top.php';

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    return;
}

?>
<style>
        .filter-section {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .btn-blue {
            background-color: blue;
            color: white;
        }
    </style>

<h2>Parking Reservation</h2>
<div class="container">
        <div class="filter-section">
            <label for="parkingZone">Filter by Parking Zone:</label>
            <select id="parkingZone" name="parkingZone">
                <option value="all">All</option>
                <option value="1">A1</option>
                <option value="2">A2</option>
                <option value="3">A3</option>
                <option value="4">B1</option>
                <option value="5">B2</option>
                <option value="6">B3</option>
            </select>
            <button onclick="filterParking()">Filter</button>
        </div>
        <div id="parkingTable">
        </div>
    </div>

    <script>
        
        function filterParking() {
            const parkingZone = document.getElementById('parkingZone').value;
            
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `filter_parking.php?zone=${parkingZone}`, true);
            xhr.onload = function () {
                if (this.status === 200) {
                    const data = JSON.parse(this.responseText);
                    let output = '<table><thead><tr><th>Parking Space</th><th>Parking Zone</th><th>Choose Desired Parking</th></tr></thead><tbody>';
                    data.forEach(row => {
                        output += `<tr><td>${row.name}-${row.area}</td><td>${row.name}</td><td><button class='btn btn-blue' onclick="reserveParking(${row.id})">Reserve Parking</button></td></tr>`;
                    });
                    output += '</tbody></table>';
                    document.getElementById('parkingTable').innerHTML = output;
                }
            };
            xhr.send();
        }

        function reserveParking(id) {
            const encodedId = encodeURIComponent(id);

            const url = `insert_reservation_info.php?id=${encodedId}`;

            window.location.href = url;
        }

        window.onload = function() {
            filterParking();
        }
    </script>

<?php
require_once 'layout_bottom.php';
