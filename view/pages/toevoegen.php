<section class="box-center">
  <div class="gold_fold">
    <h1 class="title title-center">De gouden raad van tante Kaat</h1>
    <p class="brown_intro intro_toevoegen">Schrijf zelf jouw <span class="intro_text-bold">gouden tip</span> om anderen te helpen. Je kan ervoor kiezen om een afbeelding toe te voegen en duidelijk te maken of het <span class="intro_text-bold">raad</span> is van jezelf of de anonieme <span class="intro_text-bold">tante Kaat</span>.</p>
  </div>
</section>
<form class="box-form" method="post" action="index.php?page=toevoegen" enctype="multipart/form-data">
  <input type="hidden" name="action" value="add-tip" />
  <div class="one-line form-part">
    <div>
      <label class="text text-search">Titel tip:
        <input class="form" type="text" name="titel">
      </label>
      <?php if (!empty($errors['titel'])) : ?>
        <div>
          <?php echo $errors['titel'] ?>
        </div>
      <?php endif ?>
    </div>
    <div>
      <label class="text text-search">Tag:
        <select class="form padding-unset" name="tag" id="tag">
          <option value="beauty">beauty</option>
          <option value="fashion">fashion</option>
          <option value="gezondheid">gezondheid</option>
          <option value="jongerentaal">jongerentaal</option>
          <option value="social">sociale media</option>
          <option value="dating">dating tips</option>
          <option value="activiteiten">activiteiten</option>
          <option value="party">party</option>
        </select>
      </label>
    </div>
    <?php if (!empty($errors['tag'])) : ?>
      <div>
        <?php echo $errors['tag'] ?>
      </div>
    <?php endif ?>
  </div>
  <div class="form-part">
    <label class="text">Tip:
      <textarea class="form unset" name="tip" cols="110" rows="30"></textarea>
    </label>
  </div>
  <?php if (!empty($errors['tip'])) : ?>
    <div>
      <?php echo $errors['tip'] ?>
    </div>
  <?php endif ?>
  <div class="one-line one-line-long form-part">
    <div>
      <label class="text text-search">Auteur:
        <p class="text-light small">Niet verplicht</p>
        <input class="form" type="text" name="auteur" placeholder="Tante Kaat">
      </label>
    </div>
    <div>
      <label class="text text-search">Afbeelding
        <p class="opacity">Niet verplicht</p>
        <input class="form unset padding-small" type="file" name="image" accept="image/png, image/jpeg, image/gif">
      </label>
    </div>
    <?php if (!empty($errors['tag'])) : ?>
      <div>
        <?php echo $errors['tag'] ?>
      </div>
    <?php endif ?>
  </div>
  <div class="form-part">
    <input class="button_blue" type="submit" value="Toevoegen">
  </div>
</form>