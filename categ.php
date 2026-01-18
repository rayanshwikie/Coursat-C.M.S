<section id="cards">
        <div style="text-align: center;">
          <h1 class="display-6 py-1">
            <p class="curse">
                Categories
            </p>
          </h1>
          <h1 class="display-5 " >
            <span class="stroke">
              BUILD YOUR
            </span>
              SKILLS
          </h1>
        </div>
        <br>
        <br>
        <div class="container py-3 ">
          <div class="row text-center align-items-center">
            <?php
            $query = "SELECT * FROM categories";
            $result = mysqli_query($conn, $query);

            if (!$result) {
              echo'No categories available';
              }

            while ($row = mysqli_fetch_assoc($result) ) {
   
              if (isset($row['categ_name'])) {
                echo '<div class="col-12 col-md-6 col-lg-3 mb-4">                             
                          <a href="browse.php#'.$row['id'].'" style="text-decoration: none;"><div class="cb-c text-center border-0 shadow rounded p-3 d-flex flex-row align-items-center mx-auto">
                          <div class="icon-box bg-primary text-white d-flex align-items-center justify-content-center me-3">
                            '.$row['categ_icon'].'
                          </div>
                          <h5 class="tit fw-bold mb-0" >
                          '.$row['categ_name'].'
                          </h5>
                       </div></a>
                      </div>';
                } 
                else {
                 echo "Required fields are missing in the row.";
                  }
              } 
            ?>
          
          </div>
        </div>
      </section>