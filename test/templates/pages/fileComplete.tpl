{extends file='component/framework'}


{block name='mainContent'}
    Yay, you filled the form correctly!
    {inject name='debug' type='FCFormsTest\Model\Debug'}
    {$debug->render() | nofilter}
    
    <a href="">Again! Again!</a>
    
{/block}

