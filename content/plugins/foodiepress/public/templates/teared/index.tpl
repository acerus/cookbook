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
            {if $thumb}<a href="{$photo}"><img itemprop="image" class="recipe-image" src="{$thumb}" /></a>{/if}
        </header>

        <div class="recipe-content">
            {if $desc}<p class="recipe-desc"  itemprop="description">{$desc}</p>{/if}

            <div class="recipe-add-data">
                <ul>
                    {if $preptime}<li><em>{$lang.preptime} :</em> <meta itemprop="prepTime" content="{$preptimept}">{$preptime}</li>{/if}
                    {if $cooktime}<li><em>{$lang.cooktime} :</em> <meta itemprop="cookTime" content="{$cooktimept}">{$cooktime} </li>{/if}
                    {if $yield}<li><em>{$lang.yield} :</em> <span itemprop="recipeYield">{$yield}</span></li>{/if}
                    {if $allergens} <li>{$lang.allergens} : {$allergens}</li>{/if}
                    {if $rating } <li><em>{$lang.rating}</em><span >{$rating}</span> {$lang.stars} <span >{$rating_nr}</span> {$lang.review} </li> {/if}
                </ul>
                {if $nutrtion_facts}
                <div itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">
                    <em class="nutrition">{$lang.ntfacts} :</em>
                    {foreach $nutrtion_facts item}
                    <span itemprop="{$item.nutr}">{$item.formnutr}: {$item.fact}; </span>
                    {/foreach}
                </div>
                {/if}
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

