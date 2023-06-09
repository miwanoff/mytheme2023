<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$wpaicg_custom_models = get_option('wpaicg_custom_models',[]);
$wpaicg_custom_models = array_merge(array('text-davinci-003','gpt-3.5-turbo','text-curie-001','text-babbage-001','text-ada-001','gpt-4','gpt-4-32k'),$wpaicg_custom_models);
$openai = new \WPAICG\WPAICG_OpenAI();
$openai->openai();
$wpaicg_parameters = array(
    'type' => 'topic',
    'post_type' => 'post',
    'model' => get_option('wpaicg_ai_model','gpt-3.5-turbo'),
    'temperature' => $openai->temperature,
    'max_tokens' => 3000,
    'top_p' => $openai->top_p,
    'best_of' => $openai->best_of,
    'frequency_penalty' => $openai->frequency_penalty,
    'presence_penalty' => $openai->presence_penalty,
    'prompt_title' => 'Suggest [count] title for an article about [topic]',
    'prompt_section' => 'Write [count] consecutive headings for an article about [title]',
    'prompt_content' => 'Write a comprehensive article about [title], covering the following subtopics [sections]. Each subtopic should have at least [count] paragraphs. Use a cohesive structure to ensure smooth transitions between ideas. Include relevant statistics, examples, and quotes to support your arguments and engage the reader.',
    'prompt_meta' => 'Write a meta description about [title]. Max: 155 characters.',
    'prompt_excerpt' => 'Generate an excerpt for [title]. Max: 55 words.'
);
$wpaicg_all_templates = get_posts(array(
    'post_type' => 'wpaicg_mtemplate',
    'posts_per_page' => -1
));
$wpaicg_templates = array(array(
    'title' => 'Default',
    'content' => $wpaicg_parameters
));
foreach ($wpaicg_all_templates as $wpaicg_all_template){
    $wpaicg_template_content = is_serialized($wpaicg_all_template->post_content) ? unserialize($wpaicg_all_template->post_content) : array();
    $wpaicg_template_content = wp_parse_args($wpaicg_template_content,$wpaicg_parameters);
    $wpaicg_templates[$wpaicg_all_template->ID] = array(
        'title' => $wpaicg_all_template->post_title,
        'content' => $wpaicg_template_content
    );
}
?>
<style>
    .wpaicg-form-field{
        display: flex;
        margin-bottom: 10px;
        align-items: center;
    }
    .wpaicg-form-field label{
        display: block;
        margin-right: 5px;
        width: 150px;
    }
    .wpaicg-form-field .regular-text{
        width: calc(100% - 150px);
    }
    .wpaicg-custom-parameters h3{
        margin: 0;
        background: #f1f1f1;
        padding: 10px;
        border-bottom: 1px solid #ccc;
    }
    .wpaicg-custom-parameters{
        background: #fff;
        border: 1px solid #ccc;
    }
    .wpaicg-custom-parameters-content{
        padding: 10px;
    }
    .wpaicg-custom-template-row{
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    .wpaicg_template_title_result{
        font-size: 16px;
        margin-bottom: 10px;
        font-weight: bold;
        border-radius: 4px;
        background: #e1e1e1;
        border: 1px solid #ccc;
        padding: 6px 12px;
    }
    .wpaicg_template_generate_stop{
        margin-left: 5px!important;
    }
    .wpaicg_modal{
        width: 600px;
        left: calc(50% - 300px)
    }
</style>
<form action="" method="post" class="wpaicg_custom_template_form">

    <div class="wpaicg-grid-three" style="margin-top: 20px;">
        <div class="wpaicg-grid-2">
            <div class="wpaicg-mb-10">
                <div class="mb-5" style="height:30px;display: flex;justify-content: space-between;align-items: center">
                    <div>
                        <label><input name="template[type]" checked type="radio" class="wpaicg_custom_template_type_topic" value="topic">&nbsp;<strong>Topic</strong></label>
                        &nbsp;&nbsp;&nbsp;<label><input name="template[type]" class="wpaicg_custom_template_type_title" type="radio" value="title">&nbsp;<strong>Use My Own Title</strong></label>
                    </div>
                    <div class="wpaicg-custom-template-row wpaicg_custom_template_row_type">
                        #of titles&nbsp;
                        <select class="wpaicg_custom_template_title_count" name="title_count">
                            <option value="3">3</option>
                            <option selected value="5">5</option>
                            <option value="7">7</option>
                        </select>
                        &nbsp;
                        <button class="button button-primary wpaicg_template_generate_titles" type="button">Suggest Titles</button>
                    </div>
                </div>
                <div class="wpaicg_custom_template_add_topic">
                    <div class="mb-5">
                        <input class="wpaicg_template_topic" type="text" style="width: 100%" placeholder="Topic: e.g. Mobile Phones">
                    </div>
                </div>
                <div class="wpaicg_custom_template_add_title" style="display: none">
                    <div class="mb-5">
                        <input type="text" class="wpaicg_template_title_field" style="width: 100%" placeholder="Please enter a title">
                    </div>
                </div>
            </div>
            <div class="wpaicg_template_title_result" style="display: none"></div>
            <div class="wpaicg-mb-10">
                <div class="mb-5" style="display: flex;justify-content: space-between;align-items: center">
                    <strong>Sections</strong>
                    <div class="wpaicg-custom-template-row">
                        #of sections&nbsp;
                        <select class="wpaicg_custom_template_section_count" name="section_count">
                            <?php
                            for($i = 1; $i < 13;$i++){
                                if($i%2 == 0) {
                                    echo '<option'.($i == 4 ? ' selected' : '').' value="' . esc_html($i) . '">' . esc_html($i) . '</option>';
                                }
                            }
                            ?>
                        </select>
                        &nbsp;
                        <button class="button button-primary wpaicg_template_generate_sections" type="button" disabled>Generate Sections</button>
                        <button class="button button-link-delete wpaicg_template_generate_stop" data-type="section" type="button" style="display: none">Stop</button>
                    </div>
                </div>
                <div class="mb-5">
                    <textarea class="wpaicg_template_section_result" rows="5"></textarea>
                </div>
            </div>
            <div class="wpaicg-mb-10">
                <div class="mb-5" style="display: flex;justify-content: space-between;align-items: center">
                    <strong>Content</strong>
                    <div class="wpaicg-custom-template-row">
                        #of Paragraph per Section&nbsp;
                        <select class="wpaicg_custom_template_paragraph_count" name="paragraph_count">
                            <?php
                            for($i = 1; $i < 11;$i++){
                                echo '<option'.($i == 4 ? ' selected' : '').' value="' . esc_html($i) . '">' . esc_html($i) . '</option>';
                            }
                            ?>
                        </select>
                        &nbsp;
                        <button class="button button-primary wpaicg_template_generate_content" type="button" disabled>Generate Content</button>
                        <button class="button button-link-delete wpaicg_template_generate_stop" data-type="content" type="button" style="display: none">Stop</button>
                    </div>
                </div>
                <div class="mb-5">
                    <textarea class="wpaicg_template_content_result" rows="15"></textarea>
                </div>
            </div>
            <div class="wpaicg-mb-10">
                <div class="mb-5" style="display: flex;justify-content: space-between;align-items: center">
                    <strong>Excerpt</strong>
                    <div class="wpaicg-custom-template-row">
                        <button class="button button-primary wpaicg_template_generate_excerpt" type="button" disabled>Generate Excerpt</button>
                        <button class="button button-link-delete wpaicg_template_generate_stop" data-type="excerpt" type="button" style="display: none">Stop</button>
                    </div>
                </div>
                <div class="mb-5">
                    <textarea class="wpaicg_template_excerpt_result" rows="5"></textarea>
                </div>
            </div>
            <div class="wpaicg-mb-10">
                <div class="mb-5" style="display: flex;justify-content: space-between;align-items: center">
                    <strong>Meta Description</strong>
                    <div class="wpaicg-custom-template-row">
                        <button class="button button-primary wpaicg_template_generate_meta" type="button" disabled>Generate Meta</button>
                        <button class="button button-link-delete wpaicg_template_generate_stop" data-type="meta" type="button" style="display: none">Stop</button>
                    </div>
                </div>
                <div class="mb-5">
                    <textarea class="wpaicg_template_meta_result" rows="5"></textarea>
                </div>
            </div>
            <div class="">
                <button type="button" class="button button-primary wpaicg_template_save_post" style="display: none;width: 100%">Create Post</button>
            </div>
        </div>
        <div class="wpaicg-grid-1">
            <div class="wpaicg-custom-parameters">
                <?php
                include __DIR__.'/wpaicg_custom_model_template.php';
                ?>
            </div>
        </div>
    </div>
</form>
<script>
    jQuery(document).ready(function ($){
        let  wpaicg_custom_template_form = $('.wpaicg_custom_template_form');
        let wpaicg_template_topic = $('.wpaicg_template_topic');
        let wpaicg_template_generate_titles = $('.wpaicg_template_generate_titles');
        let wpaicg_custom_template_title_count = $('.wpaicg_custom_template_title_count');
        let wpaicg_custom_template_model = $('.wpaicg_custom_template_model');
        let wpaicg_template_title_result = $('.wpaicg_template_title_result');
        let wpaicg_template_section_result = $('.wpaicg_template_section_result');
        let wpaicg_custom_template_section_count = $('.wpaicg_custom_template_section_count');
        let wpaicg_template_generate_sections = $('.wpaicg_template_generate_sections');
        let wpaicg_template_content_result = $('.wpaicg_template_content_result');
        let wpaicg_custom_template_paragraph_count = $('.wpaicg_custom_template_paragraph_count');
        let wpaicg_template_generate_content = $('.wpaicg_template_generate_content');
        let wpaicg_template_excerpt_result = $('.wpaicg_template_excerpt_result');
        let wpaicg_template_generate_excerpt = $('.wpaicg_template_generate_excerpt');
        let wpaicg_template_meta_result = $('.wpaicg_template_meta_result');
        let wpaicg_template_generate_meta = $('.wpaicg_template_generate_meta');
        let wpaicg_template_save_post = $('.wpaicg_template_save_post');
        let wpaicg_template_title_field = $('.wpaicg_template_title_field');
        let wpaicg_custom_template_title = $('.wpaicg_custom_template_title');
        let wpaicg_template_save = $('.wpaicg_template_save');
        let wpaicg_template_delete = $('.wpaicg_template_delete');
        let wpaicg_template_update = $('.wpaicg_template_update');
        let wpaicg_template_ajax_url = '<?php echo admin_url('admin-ajax.php')?>';
        let wpaicg_template_generate_stop = $('.wpaicg_template_generate_stop');
        let wpaicg_custom_template_add_topic = $('.wpaicg_custom_template_add_topic');
        let wpaicg_custom_template_add_title = $('.wpaicg_custom_template_add_title');
        let wpaicg_custom_template_type_topic = $('.wpaicg_custom_template_type_topic');
        let wpaicg_custom_template_type_title = $('.wpaicg_custom_template_type_title');
        let wpaicg_custom_template_row_type = $('.wpaicg_custom_template_row_type');
        let wpaicg_tokens = 0;
        let wpaicg_words_count = 0;
        let wpaicg_duration = 0;
        function wpaicgLoading(btn){
            btn.attr('disabled','disabled');
            if(!btn.find('spinner').length){
                btn.append('<span class="spinner"></span>');
            }
            btn.find('.spinner').css('visibility','unset');
        }
        function wpaicgRmLoading(btn){
            btn.removeAttr('disabled');
            btn.find('.spinner').remove();
        }
        wpaicg_template_generate_stop.click(function (){
            let type = $(this).attr('data-type');
            window['wpaicg_template_generator_'+type].abort();
            $(this).hide();
            wpaicgRmLoading($(this).parent().find('.button-primary'));
        });
        wpaicg_custom_template_type_topic.click(function (){
            wpaicg_custom_template_add_title.hide();
            wpaicg_custom_template_add_topic.show();
            wpaicg_custom_template_row_type.show();
        });
        wpaicg_custom_template_type_title.click(function (){
            wpaicg_custom_template_add_title.show();
            wpaicg_custom_template_add_topic.hide();
            wpaicg_custom_template_row_type.hide();
            wpaicg_template_title_result.hide();
        });
        $('.wpaicg_custom_template_select').on('change', function (){
            wpaicg_custom_template_title_count.val(3);
            wpaicg_custom_template_section_count.val(2);
            wpaicg_custom_template_paragraph_count.val(1);
            let val = parseFloat($(this).val());
            let selected = $(this).find('option:selected');
            let parameters = selected.attr('data-parameters');
            parameters = JSON.parse(parameters);
            if(val > 0){
                wpaicg_custom_template_title.val(selected.text().trim());
                wpaicg_custom_template_title.after('<input class="wpaicg_custom_template_id" type="hidden" name="id" value="'+val+'">');
                wpaicg_template_update.show();
                wpaicg_template_delete.show();
                wpaicg_template_delete.attr('data-id',val);
            }
            else{
                wpaicg_template_delete.hide();
                wpaicg_template_update.hide();
                $('.wpaicg_custom_template_id').remove();
                wpaicg_custom_template_title.val('');
            }
            $.each(parameters, function (key, item){
                $('.wpaicg_custom_template_'+key).val(item);
            })
        });
        wpaicg_template_title_field.on('input', function (){
            let val = wpaicg_template_title_field.val();
            if(val !== ''){
                wpaicg_template_generate_sections.removeAttr('disabled');
                wpaicg_template_generate_meta.removeAttr('disabled');
                wpaicg_template_generate_excerpt.removeAttr('disabled');
            }
            else{
                wpaicg_template_generate_sections.attr('disabled','disabled');
                wpaicg_template_generate_meta.attr('disabled','disabled');
                wpaicg_template_generate_excerpt.attr('disabled','disabled');
            }
        })
        $(document).on('keypress','.wpaicg_custom_template_temperature,.wpaicg_custom_template_frequency_penalty,.wpaicg_custom_template_presence_penalty,.wpaicg_custom_template_max_tokens,.wpaicg_custom_template_top_p,.wpaicg_custom_template_best_of', function (e){
            var charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46) {
                return false;
            }
            return true;
        });
        $('.wpaicg_modal_close').click(function (){
            wpaicgRmLoading(wpaicg_template_generate_titles);
        })
        wpaicg_template_delete.click(function (){
            let con = confirm('Are you sure?');
            let id = wpaicg_template_delete.attr('data-id');
            if(con) {
                $.ajax({
                    url: wpaicg_template_ajax_url,
                    data: {action: 'wpaicg_template_delete', id: id},
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function () {
                        wpaicgLoading(wpaicg_template_delete)
                    },
                    success: function (res) {
                        if (res.status === 'success') {
                            window.location.reload();
                        } else alert(res.msg);
                    }
                })
            }

        })
        $(document).on('click','.wpaicg_template_use_title', function (e){
            let btn = $(e.currentTarget);
            let title = btn.closest('.wpaicg-regenerate-title').find('input').val();
            if(title === ''){
                alert('Please choose correct title');
            }
            else{
                $('.wpaicg_modal_content').empty();
                $('.wpaicg-overlay').hide();
                $('.wpaicg_modal').hide();
                wpaicg_template_title_field.val(title);
                wpaicg_template_title_result.html('Title: '+title);
                wpaicg_template_title_result.show();
                wpaicg_template_generate_sections.removeAttr('disabled');
                wpaicg_template_generate_meta.removeAttr('disabled');
                wpaicg_template_generate_excerpt.removeAttr('disabled');
            }
        })
        // Generator Title
        wpaicg_template_generate_titles.click(function (){
            wpaicg_tokens = 0;
            wpaicg_words_count = 0;
            let topic = wpaicg_template_topic.val();
            if(topic === ''){
                alert('Please enter a topic');
            }
            else{
                wpaicg_duration = new Date();
                wpaicg_template_generate_sections.attr('disabled','disabled');
                wpaicg_template_section_result.val('');
                wpaicg_template_title_result.empty();
                wpaicg_template_title_result.hide();
                wpaicg_template_generate_content.attr('disabled','disabled');
                wpaicg_template_content_result.val('');
                wpaicg_template_generate_excerpt.attr('disabled','disabled');
                wpaicg_template_excerpt_result.val('');
                wpaicg_template_generate_meta.attr('disabled','disabled');
                wpaicg_template_meta_result.val('');
                wpaicg_template_save_post.hide();
                let data = wpaicg_custom_template_form.serialize();
                data += '&action=wpaicg_template_generator&step=titles&topic='+topic;
                $.ajax({
                    url: wpaicg_template_ajax_url,
                    data: data,
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function (){
                        wpaicgLoading(wpaicg_template_generate_titles);
                        $('.wpaicg_modal_content').empty();
                        $('.wpaicg-overlay').show();
                        $('.wpaicg_modal').show();
                        $('.wpaicg_modal_title').html('GPT AI Power - Title Suggestion Tool');
                        $('.wpaicg_modal_content').html('<p style="font-style: italic;margin-top: 5px;text-align: center;">Preparing title suggestions...</p>');
                    },
                    success: function (res){
                        wpaicgRmLoading(wpaicg_template_generate_titles);
                        if(res.status === 'success'){
                            var html = '';
                            wpaicg_tokens += parseFloat(res.tokens);
                            wpaicg_words_count += parseFloat(res.words);
                            if(res.data.length){
                                $.each(res.data, function (idx, item){
                                    html += '<div class="wpaicg-regenerate-title"><input type="text" value="'+item+'"><button class="button button-primary wpaicg_template_use_title">Use</button></div>';
                                })
                                $('.wpaicg_modal_content').html(html);
                            }
                            else{
                                $('.wpaicg_modal_content').html('<p style="color: #f00;margin-top: 5px;text-align: center;">No result</p>');
                            }
                        }
                        else{
                            alert(res.msg);
                        }
                    }
                })
            }
        });
        // Generator Sections
        wpaicg_template_generate_sections.click(function (){
            let title = wpaicg_template_title_field.val();
            if(title === ''){
                alert('Please generate title first');
            }
            else{
                let btnStop = $(this).parent().find('.wpaicg_template_generate_stop');
                wpaicg_template_section_result.val('');
                wpaicg_template_generate_content.attr('disabled','disabled');
                wpaicg_template_content_result.val('');
                wpaicg_template_generate_excerpt.attr('disabled','disabled');
                wpaicg_template_excerpt_result.val('');
                wpaicg_template_generate_meta.attr('disabled','disabled');
                wpaicg_template_meta_result.val('');
                let data = wpaicg_custom_template_form.serialize();
                data += '&action=wpaicg_template_generator&step=sections&post_title='+title;
                window['wpaicg_template_generator_section'] = $.ajax({
                    url: wpaicg_template_ajax_url,
                    data: data,
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function (){
                        wpaicgLoading(wpaicg_template_generate_sections);
                        btnStop.show();
                    },
                    success: function (res){
                        wpaicgRmLoading(wpaicg_template_generate_sections);
                        btnStop.hide();
                        wpaicg_tokens += parseFloat(res.tokens);
                        wpaicg_words_count += parseFloat(res.words);
                        if(res.status === 'success'){
                            if(res.data.length){
                                $.each(res.data, function (idx, item){
                                    let section_result = wpaicg_template_section_result.val();
                                    wpaicg_template_section_result.val(section_result+(idx === 0 ? '' : "\n")+'## '+item);
                                });
                                wpaicg_template_generate_content.removeAttr('disabled');
                            }
                            else{
                                alert('No result');
                            }
                        }
                        else {
                            alert(res.msg);
                        }
                    }
                });
            }
        });
        // Generator Post Content
        wpaicg_template_generate_content.click(function (){
            let sections = wpaicg_template_section_result.val();
            let title = wpaicg_template_title_field.val();
            if(title === ''){
                alert('Please generate title first');
            }
            else if(sections === ''){
                alert('Please generate sections first');
            }
            else{
                let btnStop = $(this).parent().find('.wpaicg_template_generate_stop');
                wpaicg_template_save_post.hide();
                wpaicg_template_content_result.val('');
                wpaicg_template_excerpt_result.val('');
                wpaicg_template_meta_result.val('');
                let data = wpaicg_custom_template_form.serialize();
                data += '&action=wpaicg_template_generator&step=content&post_title='+title+'&sections='+sections;
                window['wpaicg_template_generator_content'] = $.ajax({
                    url: wpaicg_template_ajax_url,
                    data: data,
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function (){
                        btnStop.show();
                        wpaicgLoading(wpaicg_template_generate_content);
                    },
                    success: function (res){
                        btnStop.hide();
                        wpaicgRmLoading(wpaicg_template_generate_content);
                        if(res.status === 'success'){
                            wpaicg_tokens += parseFloat(res.tokens);
                            wpaicg_words_count += parseFloat(res.words);
                            if(typeof res.data !== "undefined" && res.data !== ''){
                                wpaicg_template_content_result.val(res.data);
                                wpaicg_template_save_post.show();
                                wpaicg_template_generate_meta.removeAttr('disabled');
                                wpaicg_template_generate_excerpt.removeAttr('disabled');
                            }
                            else{
                                alert('No result')
                            }
                        }
                        else{
                            alert(res.msg);
                        }
                    }
                });
            }
        });
        // Generator Excerpt
        wpaicg_template_generate_excerpt.click(function (){
            let title = wpaicg_template_title_field.val();
            if(title === ''){
                alert('Please generate title first');
            }
            else{
                let btnStop = $(this).parent().find('.wpaicg_template_generate_stop');
                wpaicg_template_excerpt_result.val('');
                let data = wpaicg_custom_template_form.serialize();
                data += '&action=wpaicg_template_generator&step=excerpt&post_title='+title;
                window['wpaicg_template_generator_excerpt'] = $.ajax({
                    url: wpaicg_template_ajax_url,
                    data: data,
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function (){
                        btnStop.show();
                        wpaicgLoading(wpaicg_template_generate_excerpt);
                    },
                    success: function (res){
                        btnStop.hide();
                        wpaicgRmLoading(wpaicg_template_generate_excerpt);
                        if(res.status === 'success'){
                            wpaicg_tokens += parseFloat(res.tokens);
                            wpaicg_words_count += parseFloat(res.words);
                            if(typeof res.data !== "undefined" && res.data !== ''){
                                wpaicg_template_excerpt_result.val(res.data);
                            }
                            else{
                                alert('No result')
                            }
                        }
                        else{
                            alert(res.msg);
                        }
                    }
                });
            }
        });
        // Generator Meta
        wpaicg_template_generate_meta.click(function (){
            let title = wpaicg_template_title_field.val();
            if(title === ''){
                alert('Please generate title first');
            }
            else{
                let btnStop = $(this).parent().find('.wpaicg_template_generate_stop');
                wpaicg_template_meta_result.val('');
                let data = wpaicg_custom_template_form.serialize();
                data += '&action=wpaicg_template_generator&step=meta&post_title='+title;
                window['wpaicg_template_generator_meta'] = $.ajax({
                    url: wpaicg_template_ajax_url,
                    data: data,
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function (){
                        btnStop.show();
                        wpaicgLoading(wpaicg_template_generate_meta);
                    },
                    success: function (res){
                        btnStop.hide();
                        wpaicgRmLoading(wpaicg_template_generate_meta);
                        if(res.status === 'success'){
                            wpaicg_tokens += parseFloat(res.tokens);
                            wpaicg_words_count += parseFloat(res.words);
                            if(typeof res.data !== "undefined" && res.data !== ''){
                                wpaicg_template_meta_result.val(res.data);
                            }
                            else{
                                alert('No result')
                            }
                        }
                        else{
                            alert(res.msg);
                        }
                    }
                });
            }
        });
        wpaicg_template_save_post.click(function (){
            let title = wpaicg_template_title_field.val();
            let content = wpaicg_template_content_result.val();
            let excerpt = wpaicg_template_excerpt_result.val();
            let description = wpaicg_template_meta_result.val();
            let post_type = $('.wpaicg_custom_template_post_type').val();
            if(title === ''){
                alert('Please generate title first');
            }
            else if(content === ''){
                alert('Please generate content first');
            }
            else{
                let endTime = new Date();
                let duration = (endTime - wpaicg_duration)/1000;
                let model = wpaicg_custom_template_model.val();
                $.ajax({
                    url: wpaicg_template_ajax_url,
                    data: {action: 'wpaicg_template_post',post_type: post_type, model: model,duration: duration, title: title, excerpt: excerpt, content: content, description: description, tokens:wpaicg_tokens, words: wpaicg_words_count},
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function () {
                        wpaicgLoading(wpaicg_template_save_post);
                    },
                    success: function (res) {
                        wpaicgRmLoading(wpaicg_template_save_post);
                        if(res.status === 'success'){
                            window.location.href = '<?php echo admin_url('post.php?action=edit&post=')?>'+res.id;
                        }
                        else{
                            alert(res.msg);
                        }
                    }
                });
            }
        });
        function wpaicgSaveTemplate(e){
            let btn = $(e.currentTarget);
            let name = wpaicg_custom_template_title.val();
            let has_error = false;
            let temperature = $('.wpaicg_custom_template_temperature').val();
            let model = $('.wpaicg_custom_template_model').val();
            let top_p = $('.wpaicg_custom_template_top_p').val();
            let max_tokens = $('.wpaicg_custom_template_max_tokens').val();
            let best_of = $('.wpaicg_custom_template_best_of').val();
            let frequency_penalty = $('.wpaicg_custom_template_frequency_penalty').val();
            let presence_penalty = $('.wpaicg_custom_template_presence_penalty').val();
            if(name === ''){
                has_error = 'Please enter a template name';
            }
            if(!has_error && (temperature > 1 || temperature < 0)){
                has_error = 'Please enter a valid temperature value between 0 and 1.';
            }
            if(!has_error && (best_of > 20 || best_of < 0)){
                has_error = 'Please enter a valid best of value between 0 and 20.';
            }
            if(!has_error && (top_p > 1 || top_p < 0)){
                has_error = 'Please enter a valid top p value between 0 and 1.';
            }
            if(!has_error && (frequency_penalty > 2 || frequency_penalty < 0)){
                has_error = 'Please enter a valid frequency penalty value between 0 and 2.';
            }
            if(!has_error && (presence_penalty > 2 || presence_penalty < 0)){
                has_error = 'Please enter a valid presence penalty value between 0 and 2.';
            }
            if(!has_error && (model === 'gpt-3.5-turbo' || model === 'text-davinci-003') && (max_tokens > 4096 || max_tokens < 0)){
                has_error = 'Please enter a valid max tokens value between 0 and 4096.'
            }
            if(!has_error && model === 'gpt-4' && (max_tokens > 8192 || max_tokens < 0)){
                has_error = 'Please enter a valid max tokens value between 0 and 8192.'
            }
            if(!has_error && model === 'gpt-4-32k' && (max_tokens > 32768 || max_tokens < 0)){
                has_error = 'Please enter a valid max tokens value between 0 and 32768.'
            }
            if(!has_error && (model === 'text-ada-001' || model === 'text-babbage-001' || model === 'text-curie-001') && (max_tokens > 2049 || max_tokens < 0)){
                has_error = 'Please enter a valid max tokens value between 0 and 2049.'
            }
            if(has_error){
                alert(has_error);
            }
            else{
                let data = wpaicg_custom_template_form.serialize();
                data += '&action=wpaicg_save_template';
                $.ajax({
                    url: wpaicg_template_ajax_url,
                    data:data,
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function (){
                        wpaicgLoading(btn)
                    },
                    success: function (res){
                        if(res.status === 'success'){
                            window.location.reload();
                        }
                        else alert(res.msg);
                    }
                })
            }
        }
        wpaicg_template_save.click(function (e){
            $('.wpaicg_custom_template_id').remove();
            wpaicgSaveTemplate(e);
        });
        wpaicg_template_update.click(function (e){
            wpaicgSaveTemplate(e);
        });
    })
</script>
