<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart Example</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <table id="data-table" style="display: none;">
        <?php
        require 'konfig.php';

        $sql = "SELECT db_barang.nama_barang, SUM(penjualan.quantity) as total_quantity , db_barang.id
                FROM penjualan 
                JOIN db_barang ON penjualan.item_id = db_barang.kode_barang 
                GROUP BY db_barang.id";
        $result = $conn->query($sql);

        $mostSoldSql = "SELECT db_barang.nama_barang, SUM(penjualan.quantity) as total_quantity 
                    FROM penjualan 
                    JOIN db_barang ON penjualan.item_id = db_barang.kode_barang 
                    GROUP BY db_barang.nama_barang 
                    ORDER BY total_quantity DESC 
                    LIMIT 1";
        $mostSoldResult = $conn->query($mostSoldSql);
        if ($mostSoldResult->num_rows > 0) {
            $mostSoldRow = $mostSoldResult->fetch_assoc();
            $mostSoldItem = $mostSoldRow["nama_barang"];
            $mostSoldQuantity = $mostSoldRow["total_quantity"];
        } else {
            $mostSoldItem = '';
            $mostSoldQuantity = '';
        }

        $leastSoldSql = "SELECT db_barang.nama_barang, SUM(penjualan.quantity) as total_quantity 
                     FROM penjualan 
                     JOIN db_barang ON penjualan.item_id = db_barang.kode_barang 
                     GROUP BY db_barang.nama_barang 
                     ORDER BY total_quantity ASC 
                     LIMIT 1";
        $leastSoldResult = $conn->query($leastSoldSql);
        if ($leastSoldResult->num_rows > 0) {
            $leastSoldRow = $leastSoldResult->fetch_assoc();
            $leastSoldItem = $leastSoldRow["nama_barang"];
            $leastSoldQuantity = $leastSoldRow["total_quantity"];
        } else {
            $leastSoldItem = '';
            $leastSoldQuantity = '';
        }

        $totalSoldSql = "SELECT SUM(penjualan.quantity) as total_quantity FROM penjualan";
        $totalSoldResult = $conn->query($totalSoldSql);
        $totalSoldRow = $totalSoldResult->fetch_assoc();
        $totalSoldQuantity = $totalSoldRow["total_quantity"];

        $totalPemasukanSql = "SELECT SUM(penjualan.quantity * penjualan.price) as totalPemasukan FROM penjualan";
        $totalPemasukanResult = $conn->query($totalPemasukanSql);
        $totalPemasukanRow = $totalPemasukanResult->fetch_assoc();
        $totalPemasukan = $totalPemasukanRow["totalPemasukan"];

        $totalPengeluaranSql = "SELECT SUM(pembelian.quantity * pembelian.price) as totalPengeluaran FROM pembelian";
        $totalPengeluaranResult = $conn->query($totalPengeluaranSql);
        $totalPengeluaranRow = $totalPengeluaranResult->fetch_assoc();
        $totalPengeluaran = $totalPengeluaranRow["totalPengeluaran"];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='nama_barang'>" . $row["nama_barang"] . "</td>";
                echo "<td class='quantity'>" . $row["total_quantity"] . "</td>";
                echo "</tr>";
            }
        }

        $conn->close();
        ?>
    </table>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-4 mt-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Item Terjual Terbanyak</h5>
                        <p class="card-text" id="most-sold"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Item Terjual Terdikit</h5>
                        <p class="card-text" id="least-sold"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Item Terjual</h5>
                        <p class="card-text" id="total-sold"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Pemasukan</h5>
                        <p class="card-text" id="total-revenue"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Pengeluaran</h5>
                        <p class="card-text" id="total-expenses"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Keuntungan</h5>
                        <p class="card-text" id="total-margin"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <canvas id="myChart" height="75"></canvas>

    <script>
        // Extract data from the hidden table
        const table = document.getElementById('data-table');
        const rows = table.getElementsByTagName('tr');

        let labels = [];
        let data = [];

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            labels.push(cells[0].textContent);
            data.push(cells[1].textContent);
        }

        // Create the chart
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Quantity',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        document.getElementById('most-sold').innerText = "<?php echo $mostSoldItem . ' (' . $mostSoldQuantity . ')'; ?>";
        document.getElementById('least-sold').innerText = "<?php echo $leastSoldItem . ' (' . $leastSoldQuantity . ')'; ?>";
        document.getElementById('total-sold').innerText = "<?php echo $totalSoldQuantity; ?>";
        document.getElementById('total-revenue').innerText = "Rp <?php echo number_format($totalPemasukan, 0, ',', '.'); ?>";
        document.getElementById('total-expenses').innerText = "Rp <?php echo number_format($totalPengeluaran, 0, ',', '.'); ?>";
        document.getElementById('total-margin').innerText = "Rp <?php echo number_format(($totalPemasukan - $totalPengeluaran), 0, ',', '.'); ?>";
    </script>
</body>

</html>