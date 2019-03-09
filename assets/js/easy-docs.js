

jQuery(document).ready(function() {

	var ajax_url = easy_docs_ajax_url.url + '?action=easy_load_search_results&query=';

	jQuery('#easy-live-search #easy-sq').liveSearch({
		url: ajax_url
	});

});