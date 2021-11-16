<section class="tip">
    <div class="header-box">
        <img class="img-bg" src="./assets/uploads/<?php echo $tip['afbeelding'] ?>" alt="<?php echo $tip['titel'] ?>">
        <h2 class="txt-fg title title-center"><?php echo $tip['titel'] ?></h2>
    </div>
    <p class="text text-search"><?php echo $tip['tip'] ?></p>
    <div class="box-left">
        <p class="text text-light"><?php echo $tip['auteur'] ?></p>
        <!--  reacties toevoegen -->
        <div class="box-left">
            <form class="favorites_form" action="index.php?page=detail&id=<?php echo $tip['id'] ?>" method="POST">
                <input type="hidden" name="action" value="addToFavorites">
                <input type="hidden" name="tip_id" value="<?php echo $tip['id'] ?>">
                <input class="hidden favorite" type="checkbox" id="favorite" name="favorite" <?php if ($tip['favorieten'] == 1) echo 'checked' ?>>
                <label class="heart-big" for="favorite"></label>
                <input class="submit" type="submit" value="Toevoegen">
            </form>
            <form class="form_reaction box-left" action="index.php?page=detail&id=<?php echo $tip['id'] ?>" method="POST">
                <input type="hidden" name="action" value="addReactie">
                <div class='box-center margin-unset'>
                    <p class="works_text"> <?php echo $tip['amount1'] ?></p>
                    <input class="hidden works_btn" type="radio" id="works" name="reaction_id" value=1>
                    <label class="works reaction_btn" for="works">Werkt!</label>
                </div>
                <div class='box-center margin-unset'>
                    <p class="d_work_text"><?php echo $tip['amount2'] ?></p>
                    <input class="hidden d_work_btn" type="radio" id="d_work" name="reaction_id" value=2>
                    <label class="d_work reaction_btn" for="d_work">Werkt niet</label>
                </div>
                <input class="submit" type="submit" value="Toevoegen">
            </form>
        </div>
        <!-- <form action="index.php?page=detail&id=<?php echo $tip['id'] ?>" method="POST">
            <input type="hidden" name="action" value="icoonToevoegen">
            <input type="text" name="naam">
            <input type="file" name="icoon" accept="image/png, image/jpeg, image/gif">
            <input type="submit">
        </form> -->
    </div>
    <!-- opmerkingen toevoegen -->
</section>
<section>
    <h2 class="subtitle">Opmerkingen</h2>
    <form class="form_comment" action="index.php?page=detail&id=<?php echo $tip['id'] ?>" method="POST">
        <input type="hidden" name="action" value="addOpmerking">
        <div>
            <label class="text text-light">Voeg een opmerking toe:
                <textarea class="form unset" name="opmerking" cols="75" rows="10"></textarea>
            </label>
        </div>
        <?php if (!empty($errors)) : ?>
            <div>
                <?php echo $errors ?>
            </div>
        <?php endif ?>
        <input class="button_red button_down" type="submit" value="Toevoegen">
    </form>

    <div class="box-comments margin-top">
        <?php foreach ($opmerkingen as $opmerking) : ?>
            <div class="opmerking-box">
                <img class="txt-bubble" src="assets/images/txt_bubble-27.svg" alt="text_bubble" width="40" height="40">
                <p class="text"><?php echo $opmerking['opmerking'] ?></p>
            </div>
        <?php endforeach ?>
    </div>
</section>