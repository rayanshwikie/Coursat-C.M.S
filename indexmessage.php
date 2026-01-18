
<?php 
            if(isset($_SESSION[ 'errors']) && count($_SESSION[ 'errors'])>0){ 
                foreach($_SESSION['errors'] as $error){ 
            ?>
            
            <script>alertify.warning("<?= addslashes($error); ?>");</script>
            
            <?php 
                } 
                unset($_SESSION[ 'errors']); } 
                if(isset($_SESSION[ 'message'])){?>
<script>alertify.success("<?= addslashes($_SESSION['message']); ?>");</script>
<?php
                unset($_SESSION[ 'message']); }
            ?>