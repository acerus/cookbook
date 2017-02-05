<div class="foodiepress-wrapper {$style} print-only ">
    <section class="foodiepress"  itemscope itemtype="http://schema.org/Recipe">

        <img  itemprop="image" class="recipe-image" src="{$thumb}" alt="{$title}" />
        <!-- Details -->
        <div class="recipe-details">
            <ul>
                {if $yield}<li>{$lang.yield}: <strong itemprop="recipeYield">{$yield}</strong></li>{/if}
                {if $preptime}<li>{$lang.preptime}: <strong><meta itemprop="prepTime" content="{$preptimept}">{$preptime}</strong> </li>{/if }
                {if $cooktime}<li>{$lang.cooktime}: <strong><meta itemprop="cookTime" content="{$cooktimept}">{$cooktime}</strong> </li>{/if}
                {if $serving}<li>{$lang.serving}: <strong>{$serving}</strong> </li>{/if}
            </ul>
            {$print}
            
            {if $nutrtion_facts}
            <a class="popup-with-zoom-anim button nutrition-btn color" href="#small-dialog42" ><i class="fa fa-info-circle"></i>{$lang.ntfacts}</a><br/>
            <div id="small-dialog42" class="small-dialog zoom-anim-dialog mfp-hide">
                <h2 class="margin-bottom-15 nutrition-title">{$lang.ntfacts}</h2>
                <div class="nutrition-list" itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">
                <ul>
                    {foreach $nutrtion_facts item}
                        <li itemprop="{$item.nutr}">{$item.formnutr}: <strong> {$item.fact}</strong> </li>
                    {/foreach}
                </ul>
                </div>
            </div>
            {/if}
            <div class="clearfix"></div>
        </div>


        <h3 class="recipe-title" itemprop="name">{$title}</h3>
        <p class="author-data">{$lang.by}
            <a class="recipe-author" href="{$authorurl}" itemprop="author"> {$author}</a>
            <span class="recipe-data"><meta itemprop="datePublished" content="{$dateformated}">{$date}</span>
        </p>
        <!-- Text -->
        {if $desc }<div itemprop="description">{$desc}</div>{/if}

<div class="recipe-container">

        <div class="ingredients-container">
        <!-- Ingredients -->
            <h3>{$lang.ingredients}</h3>
            <ul class="ingredients">
                {foreach $ingredients item}
                    {if $item.note == 'separator'}
                        <li class="separator">{$item.name}</li>
                    {else}
                        <li><span><a target="_blank" itemprop="ingredients" href="{$item.url}">{$item.name}</a> {if $item.note} - {$item.note} {/if}</span></li>
                    {/if}
                {/foreach}
            </ul>
        </div>

        <div class="directions-container">
        <!-- Directions -->
            {if $instructions }<h3>{$lang.instructions}</h3>{/if}
            <div class="instructions" itemprop="recipeInstructions">
                {$instructions}
            </div>

            {if $allergens}
                <a class="popup-with-zoom-anim button color" href="#small-dialog43" ><i class="fa fa-info-circle"></i>{$lang.allergens }</a><br/>
                <div id="small-dialog43" class="small-dialog zoom-anim-dialog mfp-hide">
                    <h2 class="margin-bottom-10">{$lang.allergens }</h2>
                    <div class="nutrition-list" itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">
                    <p class="margin-reset">{$allergens}</p>
                    </div>
                </div>
            {/if}
        </div>
        <div class="clearfix"></div>
</div>
        {if $rating }
            <span class="review" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                <span itemprop="ratingValue">{$rating}</span> {$lang.stars} 
                <span itemprop="reviewCount">{$rating_nr}</span> {$lang.review} 
            </span>
        {/if}


    </section>
</div>

