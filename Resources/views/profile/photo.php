<div>
    <button style="width:100px" onclick="location.href='/profile'">Назад</button>
    <?php 
    
    use App\Form;
    if($user->photo!="default.png"){ ?>
    <div class="img" style="width: 100%;
    display: flex;
    flex-wrap: nowrap;
    align-content: center;
    justify-content: center;
    align-items: center;">
        <img class="profile-photo" src="/Resources/img/users/<?php echo $user->photo; ?>" alt="<?php echo $user->photo; ?>">
    </div>
    <?php 
    }else{
        echo "<h3>У вас немає фото профілю</h3>";
    }

    Form::Build([
        [
            [
                'field_type' => 'input',
                'name' => 'image',
                'label' => 'Фото профілю',
                'type' => 'file',
                'attr' => "accept='image/*' onchange='previewImage(event)'",
            ],
        ],
    ], "/profile/photo");
?></div>
<script>
    function previewImage(e){
        const file = e.target.files[0];
        const reader = new FileReader();
        reader.onload = function(event) {
            const imgElement = document.querySelector('.profile-photo');
            if(imgElement){
                imgElement.src = event.target.result;
            }else{
                const imgContainer = document.createElement('div');
                imgContainer.classList.add('img');
                const newImgElement = document.createElement('img');
                newImgElement.classList.add('profile-photo');
                newImgElement.src = event.target.result;
                imgContainer.appendChild(newImgElement);
                document.querySelector('body').insertBefore(imgContainer, document.querySelector('form'));
            }
        }
        reader.readAsDataURL(file);
    }
</script>