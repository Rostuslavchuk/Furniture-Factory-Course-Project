<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php require_once('../../../bootstrap/style.php'); ?>
    <link rel="stylesheet" href="style.css">
    <title>About Factory</title>
</head>
<body>

    <?php
        require_once('../../../sqlMain.php');
        $sqlMain = new MainSql();
    ?>

    <div class="container-md p-0 px-2">

        <div class="d-flex flex-column">

            <b class="button_text my-3" >FurnitureCo: Excellence in Furniture Manufacturing</b>

                <div class="d-flex flex-column my-2">
                    <span class="text-title">About Us</span>
                    <div class="text-custom">
                        FurnitureCo is a leading manufacturer of high-quality furniture, renowned for
                        its exceptional craftsmanship, innovative designs, and commitment to sustainability.
                        Established in 2000, our factory is located in the heart of City A, where we combine traditional
                        woodworking techniques with modern technology to create timeless pieces that enhance the beauty and
                        functionality of any space.
                    </div>
                </div>

            <div class="d-flex flex-column my-2">
                <span class="text-title">Our Facility</span>
                <div class="text-custom">
                    Our state-of-the-art factory spans over 100,000 square feet and is equipped with the latest
                    machinery and tools to ensure precision and efficiency in every step of the production process.
                    From initial design to final assembly, our dedicated team of over 200 skilled craftsmen, designers,
                    and engineers work collaboratively to produce furniture that meets the highest standards of quality.
                </div>
            </div>
            <div class="d-flex flex-column my-2" >
                <span class="text-title">Product Range</span>
                <div class="d-flex flex-column">
                    <span class="text-custom">
                        At FurnitureCo, we offer a diverse range of furniture for both residential and commercial spaces. Our product lines include:
                    </span>
                    <span class="text-custom">
                        Living Room Furniture: Sofas, coffee tables, TV stands, and recliners designed for comfort and style.
                    </span>
                    <span class="text-custom">
                        Bedroom Furniture: Beds, nightstands, dressers, and wardrobes crafted for relaxation and elegance.
                    </span>
                    <span class="text-custom">
                        Dining Room Furniture: Dining tables, chairs, and buffets that combine functionality with sophisticated design.
                    </span>
                    <span class="text-custom">
                        Office Furniture: Desks, chairs, bookcases, and conference tables tailored for productivity and professional appeal.
                    </span>
                    <span class="text-custom">
                        Outdoor Furniture: Patio sets, loungers, and garden accessories built to withstand the elements while providing comfort and aesthetic appeal.
                    </span>
                </div>
            </div>
            <div class="d-flex flex-column my-2">
                <span class="text-title">About Our Manufacturing Facility</span>
                <table class="text_table table table-bordered my-1">
                    <thead>
                        <tr>
                            <th>Factory Name</th>
                            <th>Factory Description</th>
                            <th>Factory Location</th>
                            <th>Data Established</th>
                            <th>Number Of Employers</th>
                            <th>Annual Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $array = $sqlMain->getData('factory');
                            if(count($array)):
                                foreach ($array as $row):
                        ?>
                        <tr>
                            <td><?php echo $row['factory_name']; ?></td>
                            <td><?php echo $row['factory_description']; ?></td>
                            <td><?php echo $row['factory_location']; ?></td>
                            <td><?php echo $row['established_date']; ?></td>
                            <td>
                                <?php
                                    echo $sqlMain->getSumWorkers();
                                ?>
                            </td>
                            <td><?php echo $row['annual_revenue']; ?></td>
                        </tr>
                        <?php
                            endforeach;
                            else:
                        ?>
                        <tr>
                            <td colspan="6" class="text-title text-center" >Table is Empty!</td>
                        </tr>
                        <?php
                            endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php require_once('../../../bootstrap/script.php'); ?>
</body>
</html>