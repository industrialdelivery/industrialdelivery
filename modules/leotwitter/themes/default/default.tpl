<div id="itw-container">
    <div id="itw">
        {if $leotwitter.show_header}
        <div id="itw-header">
            {if $leotwitter.twitter_icon}
                <div id="itw-twitter-icon"><a href="http://twitter.com" target="_blank">{l s='twitter' mod='leotwitter'}</a></div>
            {/if}
            {if $leotwitter.widget_type == 'timeline'}
                <a href="https://twitter.com/{$data->tweets[0]->screenName}" target="_blank">
                    <img src="{$data->tweets[0]->profileImage}" class="itw-avatar" />
                    <span class="itw-display-name">{$data->tweets[0]->displayName}</span>
                    <span class='itw-screen-name'> @{$data->tweets[0]->screenName}</span>
                </a>
                <div style="clear: both;"></div>
            {else}
                {if $leotwitter.link_search}
                    <a href="https://twitter.com/search/{$leotwitter.search_query}" target="_blank">{$leotwitter.search_title}</a>
                {else}
                    {$leotwitter.search_title}
                {/if}
            {/if}
        </div>
        {/if}
        <div id="itw-tweets">
        {foreach from=$data->tweets item=tweet key=key name=tweets}
            <div class="itw-tweet-container{if $smarty.foreach.tweets.last} itw-last{/if}">
                {if $leotwitter.display_avatars}
                    <div>
                        <a href="https://twitter.com/intent/user?screen_name={$tweet->screenName}" target="_blank">
                            <img src="{$tweet->profileImage}" class="itw-avatar" style="width: 35px;" />
                        </a>
                    </div>
                    <div class="itw-tweet" style="padding-left: 40px;">
                {else}
                    <div class="itw-tweet">
                {/if}
                   {if $leotwitter.display_name}
                    <a href="https://twitter.com/intent/user?screen_name={$tweet->screenName}" target="_blank">{$tweet->screenName}</a>
                    {/if}
                    {$tweet->text}
                </div>
                <div class="itw-tweet-data">
                    {if $leotwitter.display_time}
                    <a href="https://twitter.com/{$tweet->screenName}/statuses/{$tweet->id}" target="_blank">{$tweet->time}</a>
                    {if $leotwitter.reply_link || $leotwitter.retweet_link || $leotwitter.ravorite_link}
                    &bull;
                    {/if}
                    {/if}
                    {if $leotwitter.reply_link}
                    <a href="https://twitter.com/intent/tweet?in_reply_to={$tweet->id}" target="_blank">{l s='reply' mod='leotwitter'}</a>
                        {if $leotwitter.retweet_link || $leotwitter.ravorite_link}
                        &bull;
                        {/if}
                    {/if}
                    {if $leotwitter.retweet_link}
                    <a href="https://twitter.com/intent/retweet?tweet_id={$tweet->id}" target="_blank">{l s='retweet' mod='leotwitter'}</a>
                        {if $leotwitter.ravorite_link}
                        &bull;
                        {/if}
                    {/if}
                    {if $leotwitter.ravorite_link}
                    <a href="https://twitter.com/intent/favorite?tweet_id={$tweet->id}" target="_blank">{l s='favorite' mod='leotwitter'}</a>
                    {/if}
                </div>
            </div>
        {/foreach}
        </div>
    </div>
</div>
{if $leotwitter_style}
    <style type="text/css">
        {$leotwitter_style}
    </style>
{/if}