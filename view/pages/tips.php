<section class="tip-bg">
    <h2 class="title title-center">Met iedere tip een jaartje jonger</h2>
</section>
<section>
    <h2 class="hidden">tips</h2>
    <div class="box-center">
        <form class="box-center search_form" method="get" action="index.php?page=tips">
            <input type="hidden" name="page" value="tips">
            <input type="hidden" value="search" name="action">
            <label class="text text-search" for="search">Zoeken</label>
            <input class="form search" type="search" id="search" name="title" value="<?php if (!empty($_GET['titel'])) {
                                                                                            echo $_GET['titel'];
                                                                                        } ?>">
            <input class="submit button_blue" type="submit" value="Zoek">
        </form>
        <!-- buttons om te sorteren -->
        <form class="box-sort" action="index.php" method="GET">
            <input type="hidden" name="page" value="tips">
            <input type="hidden" name="action" value="filter">
            <input class="hidden input_btn" type="radio" id="beauty" name="tags" value="beauty">
            <label class="margin-btm button_blue" for="beauty">beauty</label>
            <input class="hidden input_btn" type="radio" id="fashion" name="tags" value="fashion">
            <label class="margin-btm button_blue" for="fashion">fashion</label>
            <input class="hidden input_btn" type="radio" id="gezondheid" name="tags" value="gezondheid">
            <label class="margin-btm button_blue" for="gezondheid">gezondheid</label>
            <input class="hidden input_btn" type="radio" id="jongerentaal" name="tags" value="jongerentaal">
            <label class="margin-btm button_blue" for="jongerentaal">jongerentaal</label>
            <input class="hidden input_btn" type="radio" id="sociale_media" name="tags" value="social">
            <label class="margin-btm button_blue btn-wide" for="sociale_media">sociale media</label>
            <input class="hidden input_btn" type="radio" id="dating_tips" name="tags" value="dating">
            <label class="margin-btm button_blue" for="dating_tips">dating tips</label>
            <input class="hidden input_btn" type="radio" id="activiteiten" name="tags" value="activiteiten">
            <label class="margin-btm button_blue" for="activiteiten">activiteiten</label>
            <input class="hidden input_btn" type="radio" id="party" name="tags" value="party">
            <label class="margin-btm button_blue" for="party">party</label>
            <input class="hidden input_btn" type="radio" id="favorieten" name="tags" value="favorieten">
            <label class="margin-btm button_blue" for="favorieten">favorieten</label>
            <input class="submit" type="submit" value="toevoegen">
        </form>
        <form class="filter-form" action="index.php" method="GET">
            <input type="hidden" name="page" value="tips">
            <input type="hidden" name="action" value="sort">
            <label class="text">Sorteren op:
                <select class="sort-tips" name="sort" id="sort">
                    <option value="datum">datum</option>
                    <option value="populairste">populairste</option>
                </select>
            </label>
            <input class="submit" type="submit" value="toevoegen">
        </form>
    </div>
    <ul class="list">
        <?php foreach ($tips as $tip) : ?>
            <li class="box-tip">
                <a class="unset-link" href="index.php?page=detail&id=<?php echo $tip['id'] ?>">
                    <img class="crop" src="assets/uploads/<?php echo $tip['afbeelding'] ?>" alt="<?php echo $tip['titel'] ?>">
                    <h2 class="text text-search"><?php echo $tip['titel'] ?></h2>
                </a>
                <div class="box-left">
                    <p class="text text-light"><?php echo $tip['auteur'] ?></p>
                    <form class="smallFavoritesForm" action="index.php?page=tips" method="post">
                        <input type="hidden" name="action" value="addToFavorites">
                        <input type="hidden" name="tip_id" value="<?php echo $tip['id'] ?>">
                        <input class="hidden favorite" type="checkbox" id="favorite<?php echo $tip['id'] ?>" name="favorite" <?php if ($tip['favorieten'] == 1) echo 'checked' ?>>
                        <label class="heart" for="favorite<?php echo $tip['id'] ?>"></label>
                        <input class="submit" type="submit" value="Toevoegen">
                    </form>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
</section>