{extends file='component/framework'}


{block name='mainContent'}
    {inject name='form' type='FCFormsTest\ExampleForms\SignupExampleForm'}
    {inject name='formRender' type='FCForms\Render'}
    {$formRender->render($form) | nofilter}
{/block}

