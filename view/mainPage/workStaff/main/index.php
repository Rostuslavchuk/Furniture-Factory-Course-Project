<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?php require_once('../../../../bootstrap/style.php'); ?>
    <title>Departments</title>
</head>
<body>
    
    
    <style>
        .none{
            display: none;
        }
        .text-custom{
            font-size: 15px;
        }
        .text_table{
            font-size: 12px;
        }
        .text-title{
            font-size: 24px;
        }
        .button_text{
            font-size: 17px;
        }
        
        .production_department, .design_department,
        .hr_department, .it_department, .quality_control_department{
            display: none;
        }
        .production_department:target{
            display: block;
        }
        .design_department:target{
            display: block;
        }
        .hr_department:target{
            display: block;
        }
        .it_department:target{
            display: block;
        }
        .quality_control_department:target{
            display: block;
        }
        
        .active{
            background-color: red;
            border-radius: 20%;
        }
        
        @media screen and (max-width: 789px) {
            .text-custom{
                font-size: 13px;
            }
            .text-title{
                font-size: 15px;
            }
            .button_text{
                font-size: 14px;
            }
            .text_table{
                font-size: 10px;
            }
            #main, #main_footer{
                display: flex;
                flex-direction: column;
                align-items:center;
            }
            #main .card, #main_footer .card{
                margin-bottom: 10px;
            }
            
        }
    </style>
    
    
    
    
    <div class="container-md p-0 d-flex flex-column px-5 gap-3">

        <div class="d-flex justify-content-between my-3" >
            <h2 class="text-title" >Departments</h2>
            <button class="btn btn-secondary button_text" id="back_btn">Back</button>
        </div>

        <div class="d-flex justify-content-between" id="main">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="images/ProductionDepartment.jpg" alt="Production Department">
                <div class="card-body">
                    <h5 class="card-title text-title">Production Department</h5>
                    <p class="card-text text-custom">
                        The Production Department at FurnitureCo.
                        crafts high-quality furniture with precision and care.
                        Skilled workers and advanced machinery transform raw materials into durable,
                        beautiful pieces, ensuring excellence in every product.
                    </p>
                    <a id="production_department" href="#" class="btn btn-secondary button_text">View Staff</a>
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="images/DesignDepartment.jpg" alt="Design Department">
                <div class="card-body">
                    <h5 class="card-title text-title">Design Department</h5>
                    <p class="card-text text-custom">
                        The Design Department at FurnitureCo. innovates and creates stylish furniture concepts.
                        Talented designers blend aesthetics with functionality,
                        producing unique designs that cater to diverse tastes and enhance living spaces.
                    </p>
                    <a id="design_department" href="#" class="btn btn-secondary button_text">View Staff</a>
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="images/ItDepartment.jpg" alt="It Department">
                <div class="card-body">
                    <h5 class="card-title text-title">It Department</h5>
                    <p class="card-text text-custom">
                        The IT Department at FurnitureCo. ensures smooth technological operations.
                        They manage software, hardware, and network infrastructure,
                        supporting efficient processes across all departments and enhancing
                        productivity and customer experience.
                    </p>
                    <a id="it_department" href="#" class="btn btn-secondary button_text">View Staff</a>
                </div>
            </div>
        </div>

        <div class="mt-3" style="display: flex; justify-content: space-around;" id="main_footer">
            
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="images/HrDepartment.jpg" alt="Hr Department">
                <div class="card-body">
                    <h5 class="card-title text-title">Hr Department</h5>
                    <p class="card-text text-custom">
                        The HR Department at FurnitureCo. is dedicated to managing and supporting
                        the company's most valuable asset: its people. They handle recruitment,
                        training, employee relations, and benefits, ensuring a positive and productive
                        work environment for all staff.
                    </p>
                    <a id="hr_department" href="#" class="btn btn-secondary button_text">View Staff</a>
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="images/QualityControlDepartment.jpg" alt="Quality Control Department">
                <div class="card-body">
                    <h5 class="card-title text-title">Quality Control Department</h5>
                    <p class="card-text text-custom">
                        The Quality Control Department at FurnitureCo. conducts thorough
                        inspections and tests to ensure every product meets our high standards
                        for quality and safety. They play a crucial role in maintaining the
                        excellence and reliability of our furniture.
                    </p>
                    <a id="quality_control_department" href="#" class="btn btn-secondary button_text">View Staff</a>
                </div>
            </div>
            
        </div>
    </div>

    <?php require_once('../../../../bootstrap/script.php'); ?>
    <script src="script.js" defer ></script>
</body>
</html>