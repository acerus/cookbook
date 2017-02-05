<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><div class="foodiepress-wrapper <?php echo $this->scope["style"];?> print-only ">
    <section class="foodiepress"  itemscope itemtype="http://schema.org/Recipe">

        <img style="display:none;" class="recipe-image" src="<?php echo $this->scope["thumb"];?>" alt="<?php echo $this->scope["title"];?>" />
        <!-- Details -->
        <div class="recipe-details">
            <ul>
                <?php if ((isset($this->scope["yield"]) ? $this->scope["yield"] : null)) {
?><li><?php echo $this->scope["lang"]["yield"];?>: <strong itemprop="recipeYield"><?php echo $this->scope["yield"];?></strong></li><?php 
}?>

                <?php if ((isset($this->scope["preptime"]) ? $this->scope["preptime"] : null)) {
?><li><?php echo $this->scope["lang"]["preptime"];?>: <strong><meta itemprop="prepTime" content="<?php echo $this->scope["preptimept"];?>"><?php echo $this->scope["preptime"];?></strong> </li><?php 
}?>

                <?php if ((isset($this->scope["cooktime"]) ? $this->scope["cooktime"] : null)) {
?><li><?php echo $this->scope["lang"]["cooktime"];?>: <strong><meta itemprop="cookTime" content="<?php echo $this->scope["cooktimept"];?>"><?php echo $this->scope["cooktime"];?></strong> </li><?php 
}?>

                <?php if ((isset($this->scope["serving"]) ? $this->scope["serving"] : null)) {
?><li><?php echo $this->scope["lang"]["serving"];?>: <strong><?php echo $this->scope["serving"];?></strong> </li><?php 
}?>

            </ul>
            <?php echo $this->scope["print"];?>


            <?php if ((isset($this->scope["nutrtion_facts"]) ? $this->scope["nutrtion_facts"] : null)) {
?>
            <a class="popup-with-zoom-anim button nutrition-btn color" href="#small-dialog42" ><i class="fa fa-info-circle"></i><?php echo $this->scope["lang"]["ntfacts"];?></a><br/>
            <div id="small-dialog42" class="small-dialog zoom-anim-dialog mfp-hide">
                <h2 class="margin-bottom-15 nutrition-title"><?php echo $this->scope["lang"]["ntfacts"];?></h2>
                <div class="nutrition-list" itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">
                <ul>
                    <?php 
$_fh0_data = (isset($this->scope["nutrtion_facts"]) ? $this->scope["nutrtion_facts"] : null);
if ($this->isTraversable($_fh0_data) == true)
{
	foreach ($_fh0_data as $this->scope['item'])
	{
/* -- foreach start output */
?>
                        <li itemprop="<?php echo $this->scope["item"]["nutr"];?>"><?php echo $this->scope["item"]["formnutr"];?>: <strong> <?php echo $this->scope["item"]["fact"];?></strong> </li>
                    <?php 
/* -- foreach end output */
	}
}?>

                </ul>
                </div>
            </div>
            <?php 
}?>

            <div class="clearfix"></div>
        </div>


        <h3 class="recipe-title" itemprop="name"><?php echo $this->scope["title"];?></h3>
        <p class="author-data"><?php echo $this->scope["lang"]["by"];?>

            <a class="recipe-author" href="<?php echo $this->scope["authorurl"];?>" itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name"> <?php echo $this->scope["author"];?> </span></a>
            <span class="recipe-data"><meta itemprop="datePublished" content="<?php echo $this->scope["dateformated"];?>"><?php echo $this->scope["date"];?></span>
        </p>
        <!-- Text -->
        <?php if ((isset($this->scope["desc"]) ? $this->scope["desc"] : null)) {
?><div itemprop="description"><?php echo $this->scope["desc"];?></div><?php 
}?>



        <!-- Ingredients -->
        <h3><?php echo $this->scope["lang"]["ingredients"];?></h3>
        <ul class="ingredients">
            <?php 
$_fh1_data = (isset($this->scope["ingredients"]) ? $this->scope["ingredients"] : null);
if ($this->isTraversable($_fh1_data) == true)
{
	foreach ($_fh1_data as $this->scope['item'])
	{
/* -- foreach start output */
?>
                <?php if ((isset($this->scope["item"]["note"]) ? $this->scope["item"]["note"]:null) == 'separator') {
?>
                    <li class="separator"><?php echo $this->scope["item"]["name"];?></li>
                <?php 
}
else {
?>
                    <li><span><a  target="_blank" href="<?php echo $this->scope["item"]["url"];?>" itemprop="ingredients"><?php echo $this->scope["item"]["name"];?></a> <?php if ((isset($this->scope["item"]["note"]) ? $this->scope["item"]["note"]:null)) {
?> - <?php echo $this->scope["item"]["note"];?> <?php 
}?></span></li>
                <?php 
}?>

            <?php 
/* -- foreach end output */
	}
}?>

        </ul>

        <!-- Directions -->
        <h3><?php echo $this->scope["lang"]["instructions"];?></h3>
        <div class="instructions" itemprop="recipeInstructions">
            <?php echo $this->scope["instructions"];?>

        </div>

        <?php if ((isset($this->scope["allergens"]) ? $this->scope["allergens"] : null)) {
?>
            <a class="popup-with-zoom-anim button color" href="#small-dialog43" ><i class="fa fa-info-circle"></i><?php echo $this->scope["lang"]["allergens"];?></a><br/>
            <div id="small-dialog43" class="small-dialog zoom-anim-dialog mfp-hide">
                <h2 class="margin-bottom-10"><?php echo $this->scope["lang"]["allergens"];?></h2>
                <div class="nutrition-list" itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">
                <p class="margin-reset"><?php echo $this->scope["allergens"];?></p>
                </div>
            </div>
        <?php 
}?>



        <?php if ((isset($this->scope["rating"]) ? $this->scope["rating"] : null)) {
?>
            <span class="review" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                <span itemprop="ratingValue"><?php echo $this->scope["rating"];?></span> <?php echo $this->scope["lang"]["stars"];?> 
                <span itemprop="reviewCount"><?php echo $this->scope["rating_nr"];?></span> <?php echo $this->scope["lang"]["review"];?> 
            </span>
        <?php 
}?>



    </section>
</div>

<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>