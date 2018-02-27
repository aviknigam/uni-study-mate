<?php
    require __DIR__ . '/../core/init.php';
    $title = 'Buy and Sell Textbooks';
    $description = 'Buy and sell university textbooks for affordable prices.';
    $navbar = 'textbooks';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../includes/head.php'; ?>
        <link rel="stylesheet" type="text/css" href="/dist/css/textbooks.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="/dist/css/responsive.css?<?php echo time(); ?>">
    </head>

    <body>
        <!-- Navbar -->
            <?php include '../includes/navbar.php'; ?>

        <!-- Landing -->
            <div class="page-section bg-blue">
                <div class="container">
                    <div class="landing text-d-white">
                        <h1 class="landing-heading h-white">Textbook Marketplace</h1>
                        <p>Buy and sell university textbooks for no extra commission!</p>
                        <p>Simply <a href="/account/register" class="btn btn-dark btn-transparent btn-block">create an account</a> and advertise your books for free!</p>
                        <img src="/dist/img/bird-happy.png" alt="Earn a few extra bucks which are just lying around">
                    </div>    
                </div>
            </div>

        <!-- Browse Textbooks -->
            <div class="page-section">
                <div class="container flex ffcolwrap align-items-center text-align-center">
                    <?php
                        if (!isset($_SESSION['userID'])) {
                            echo '<a href="/account/register" class="btn btn-dark btn-block">Sell a Textbook</a>';
                        } else {
                            echo '<a href="/account/sell" class="btn btn-dark btn-block">Sell a Textbook</a>';
                        }
                    ?>
                    <h2 class="h-grey">Browse the Latest Textbooks</h2>
                    <input type="text" id="search" placeholder="Type to filter !">
                </div>
            </div>

        <!-- List of Textbooks -->
            <div class="page-section">
                <div class="container">
                    <div class="table-responsive">
                        <table id="table" class="table table-hover">
                            <tbody>
                                <?php
                                    // Listings Query
                                    $listings_query = "SELECT * FROM listings WHERE listingType = 'textbook' ORDER BY listingID DESC";
                                    $listings_query = $conn->query($listings_query);

                                    while ($row = $listings_query->fetch_assoc()) {
                                        $textbookID = $row['textbookID'];
                                        $userID = $row['userID'];
                                        $listingPrice = $row['listingPrice'];
                                        $listingQuality = $row['listingQuality'];
                                        $listingDescription = $row['listingDescription'];
                                        $listingSlug = $row['listingSlug'];
                                        
                                        // Textbooks Query
                                        $textbooks_query = "SELECT * FROM textbooks LEFT JOIN listings ON textbooks.textbookID = listings.textbookID WHERE listings.textbookID = $textbookID";
                                        $textbooks_query = $conn->query($textbooks_query);

                                        while ($row = $textbooks_query->fetch_assoc()) {
                                            $textbookISBN = $row['textbookISBN'];
                                            $textbookTitle = $row['textbookTitle'];
                                            $textbookYear = $row['textbookYear'];
                                            $textbookAuthor = $row['textbookAuthor'];
                                            $textbookEdition = $row['textbookEdition'];
                                            $textbookPrice = $row['textbookPrice'];
                                            $textbookURK = $row['textbookURL'];
                                        }

                                        // Users Query
                                        $users_query = "SELECT * FROM users LEFT JOIN listings ON users.userID = listings.userID WHERE listings.userID = $userID";
                                        $users_query = $conn->query($users_query);

                                        while ($row = $users_query->fetch_assoc()) {
                                            $universityID = $row['universityID'];
                                        }
                                        
                                        // University Query
                                        $universities_query = "SELECT * FROM universities LEFT JOIN users ON universities.universityID = users.universityID WHERE users.universityID = $universityID";
                                        $universities_query = $conn->query($universities_query);
                                        
                                        while ($row = $universities_query->fetch_assoc()) {
                                            $universityName = $row['universityName'];
                                            $universityShortName = $row['universityShortName'];
                                        }
                                        
                                        // Each Table Row
                                        echo "
                                            <tr>
                                                <td style='width: 40%;'><a href='/textbooks/$listingSlug' class='textbook-link'>$textbookTitle ($textbookYear)</a><br /><br /><span>$textbookAuthor</span><br /><span>Edition: " .ordinal($textbookEdition). "</span><br /><span><strong>Quality: $listingQuality/5</strong></span></td>
                                                <td style='width: 40%;'><span><strong>$universityName</strong></span><br /><br /><span>$listingDescription</span></td>
                                                <td width='10%'><span>ISBN: <br /><br />$textbookISBN</span></td>
                                                <td width='10%' style='vertical-align: middle'><a href='/textbooks/$listingSlug' class='btn btn-dark btn-block'>Buy for &dollar;$listingPrice</a></td>
                                            </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php include '../includes/ads-responsive.php'; ?>
                </div>
            </div>

		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E=" crossorigin="anonymous"></script>
            <script src="/dist/js/filter.js"></script>
    </body>
</html>