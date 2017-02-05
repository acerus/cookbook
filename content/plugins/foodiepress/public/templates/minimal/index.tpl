<div class="purerecipe-wrapper {$style}">
    <section class="purerecipe"  itemscope itemtype="http://schema.org/Recipe">
        <header>
            <div>
                <h3 itemprop="name">{$title}</h3>
                <p class="author-data">{$lang.by}
                    <a class="recipe-author" href="{$authorurl}" itemprop="author"> {$author}</a>
                    <span class="recipe-data"><meta itemprop="datePublished" content="{$dateformated}">{$date}</span>
                </p>
            </div>

        </header>


        <div class="recipe-content">
            <div class="recipe-top-content">
                <img itemprop="image" class="recipe-image" src="{$thumb}" />
                {if $desc }<p class="recipe-desc"  itemprop="description">{$desc}</p>{/if}

                <div class="recipe-add-data">

                    {if $preptime}<em>{$lang.preptime} :</em> <meta itemprop="prepTime" content="{$preptimept}">{$preptime}.{/if }
                    {if $cooktime}<em>{$lang.cooktime} :</em> <meta itemprop="cookTime" content="{ $cooktimept }">{$cooktime}{/if}
                    {if $yield}<em>{$lang.yield} :</em> <span itemprop="recipeYield">{$yield}</span>{/if}
                    {if $allergens} {$lang.allergens } : {$allergens}  {/if}
                    {if $rating }<p class="review hreview-aggregate"><em>{$lang.rating}</em>  <span class="rating"><span class="average">{$rating}</span> {$lang.stars} <span class="count">{$rating_nr}</span> {$lang.review}</span></p>{/if}

                    {if $nutrtion_facts}
                    <div itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">
                        <em class="nutrition">{$lang.ntfacts} :</em>
                        {foreach $nutrtion_facts item}
                        <span itemprop="{$item.nutr}">{$item.formnutr}: {$item.fact}; </span>
                        {/foreach}
                    </div>
                    {/if}
                </div>
            </div>
            <div class="ingredients-container">
                <h4>{$lang.ingredients}</h4>
                <ul class="ingredients">
                    {foreach $ingredients item}
                    {if $item.note == 'separator'}
                    <li class="separator">{$item.name}</li>
                    {else}
                    <li itemprop="ingredients"><a href="{$item.url}">{$item.name}</a> {if $item.note} - {$item.note} {/if}</li>
                    {/if}
                    {/foreach}
                </ul>
            </div>
            <h4>{$lang.instructions}</h4>
            <div class="instructions" itemprop="recipeInstructions">
                {$instructions}
            </div>
            {if $print} {$print} {/if}
        </div>
    </section>
</div>

