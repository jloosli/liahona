<div class="liahona-quotations-control">
	<p>Enter the quotation, author&rsquo;s name and a URL (if applicable) for  the quote to link to. Also, in the quotation
    area above, don&rsquo;t format it. It's best to just enter text so the plugin functions correctly.</p>
 
	<label>Author Name</label>

	<p>
		<input type="text" name="_li_quotations_meta[author]" value="<?php if(!empty($meta['author'])) echo $meta['author']; ?>"/>
	</p>

	<label>URL <span>(optional)</span></label>
 
	<p>
        <input type="text" name="_li_quotations_meta[url_title]"
               value="<?php if(!empty($meta['url_title'])) echo $meta['url_title']; ?>"
                placeholder="Link Title"
            />
        <input type="url" name="_li_quotations_meta[URL]" value="<?php if(!empty($meta['URL'])) echo $meta['URL']; ?>"
            placeholder="Link URL"
            />
	</p>
 
</div>