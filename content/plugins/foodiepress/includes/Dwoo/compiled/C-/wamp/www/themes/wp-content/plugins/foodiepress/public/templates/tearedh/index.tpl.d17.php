<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><div class="purerecipe-wrapper <?php echo $this->scope["style"];?>">
    <section class="purerecipe"  itemscope itemtype="http://schema.org/Recipe">
        <header>
            <div>
                <h3 itemprop="name"><?php echo $this->scope["title"];?></h3>
                <p class="author-data"><?php echo $this->scope["lang"]["by"];?>

                    <a class="recipe-author" href="<?php echo $this->scope["authorurl"];?>" itemprop="author"> <?php echo $this->scope["author"];?></a>
                    <span class="recipe-data"><meta itemprop="datePublished" content="<?php echo $this->scope["dateformated"];?>"><?php echo $this->scope["date"];?></span>
                </p>
            </div>
            <?php if ((isset($this->scope["thumb"]) ? $this->scope["thumb"] : null)) {
?><a href="<?php echo $this->scope["photo"];?>"><img itemprop="image" class="recipe-image" src="<?php echo $this->scope["thumb"];?>" /></a><?php 
}?>

        </header>


        <div class="recipe-content">
            <?php if ((isset($this->scope["desc"]) ? $this->scope["desc"] : null)) {
?><p class="recipe-desc"  itemprop="description"><?php echo $this->scope["desc"];?></p><?php 
}?>


            <div class="recipe-add-data">
                <ul>
                    <?php if ((isset($this->scope["preptime"]) ? $this->scope["preptime"] : null)) {
?><li><em><?php echo $this->scope["lang"]["preptime"];?> :</em> <meta itemprop="prepTime" content="<?php echo $this->scope["preptimept"];?>"><?php echo $this->scope["preptime"];?></li><?php 
}?>

                    <?php if ((isset($this->scope["cooktime"]) ? $this->scope["cooktime"] : null)) {
?><li><em><?php echo $this->scope["lang"]["cooktime"];?> :</em> <meta itemprop="cookTime" content="<?php echo $this->scope["cooktimept"];?>"><?php echo $this->scope["cooktime"];?> </li><?php 
}?>

                    <?php if ((isset($this->scope["rating"]) ? $this->scope["rating"] : null)) {
?><li class="review hreview-aggregate"><em><?php echo $this->scope["lang"]["rating"];?></em>  <span class="rating"><span class="average"><?php echo $this->scope["rating"];?></span> <?php echo $this->scope["lang"]["stars"];?> <span class="count"><?php echo $this->scope["rating_nr"];?></span> <?php echo $this->scope["lang"]["review"];?></span></li><?php 
}?>

                    <?php if ((isset($this->scope["yield"]) ? $this->scope["yield"] : null)) {
?><li><em><?php echo $this->scope["lang"]["yield"];?> :</em> <span itemprop="recipeYield"><?php echo $this->scope["yield"];?></span></li><?php 
}?>

                    <?php if ((isset($this->scope["allergens"]) ? $this->scope["allergens"] : null)) {
?> <li><?php echo $this->scope["lang"]["allergens"];?> : <?php echo $this->scope["allergens"];?></li><?php 
}?>

                </ul>
                <?php if ((isset($this->scope["nutrtion_facts"]) ? $this->scope["nutrtion_facts"] : null)) {
?>
                <div itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">
                    <em class="nutrition"><?php echo $this->scope["lang"]["ntfacts"];?> :</em>
                    <?php 
$_fh0_data = (isset($this->scope["nutrtion_facts"]) ? $this->scope["nutrtion_facts"] : null);
if ($this->isTraversable($_fh0_data) == true)
{
	foreach ($_fh0_data as $this->scope['item'])
	{
/* -- foreach start output */
?>
                    <span itemprop="<?php echo $this->scope["item"]["nutr"];?>"><?php echo $this->scope["item"]["formnutr"];?>: <?php echo $this->scope["item"]["fact"];?>; </span>
                    <?php 
/* -- foreach end output */
	}
}?>

                </div>
                <?php 
}?>

            </div>

            <div class="ingredients-container">
                <h4><?php echo $this->scope["lang"]["ingredients"];?></h4>
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
                        <li itemprop="ingredients"><a href="<?php echo $this->scope["item"]["url"];?>"><?php echo $this->scope["item"]["name"];?></a> <?php if ((isset($this->scope["item"]["note"]) ? $this->scope["item"]["note"]:null)) {
?> - <?php echo $this->scope["item"]["note"];?> <?php 
}?></li>
                        <?php 
}?>

                    <?php 
/* -- foreach end output */
	}
}?>

                </ul>
            </div>
            <h4><?php echo $this->scope["lang"]["instructions"];?></h4>
            <div class="instructions" itemprop="recipeInstructions">
                <?php echo $this->scope["instructions"];?>

            </div>
            <?php if ((isset($this->scope["print"]) ? $this->scope["print"] : null)) {
?> <?php echo $this->scope["print"];?> <?php 
}?>

        </div>
    </section>
</div>

<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>