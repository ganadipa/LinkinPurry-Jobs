<div id="companyInfoContainer" class='company-info' >
  <div class="company-header">
    <div>
      <h2 class="company-name">
        <?= $company['name']; ?>
      </h2>
      <div class="company-location">
        <i data-lucide="map-pin" class='lucide-sm mr-icon-sm'></i>
        <span>
            <?= $company['location'] ?>
        </span>
      </div>
    </div>
  </div>
  <p class="company-about">
    <?php
        if (isset($company['about'])) echo $company['about']; 
     ?>
  </p>
</div>
