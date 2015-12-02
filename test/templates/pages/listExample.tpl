{extends file='component/framework'}


{block name='mainContent'}
   
    {inject name='form' type='FCFormsTest\ExampleForms\ListExamplesForm'}
    {inject name='formRender' type='FCForms\Render'}
    
    {$formRender->render($form) | nofilter}
    
{/block}

