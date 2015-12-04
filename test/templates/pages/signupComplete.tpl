{extends file='component/framework'}


{block name='mainContent'}
    Yay, you filled the form correctly!
    {inject name='signup' type='FCFormsTest\Model\SignupModel'}

    The email registered is {$signup->getEmail()} and the users choice for receiving marketing messages 
    is:
    
    {if $signup->getSpamMe()}
         spam enabled
    {else}
         no spam please.
    {/if}
{/block}

