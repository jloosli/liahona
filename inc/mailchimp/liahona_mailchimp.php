<?php
/**
This Example shows how to add a new API key using the MCAPI.php class and do
some basic error checking.
 **/

class MC_LIAHONA
{
    protected
        $api = NULL,
        //API Key - see http://admin.mailchimp.com/account/api
        $apikey = '8027316501dbac4ad5b3501ed52f887b-us4',

        // A List Id to run examples against. use lists() to view all
        // Also, login to MC account, go to List, then List Tools, and look for the List ID entry
        $listId = '03548e1394',

        //just used in xml-rpc examples
        $apiUrl = 'http://api.mailchimp.com/1.3/';

    function __construct()
    {
        // actions
        add_action('comment_post', array($this,'add_comment_meta'), 1);
        add_action('comment_approved_', array($this,'subscription_add'), 10, 2);
        add_action('comment_post', array($this,'subscription_add'), 60, 2);
    }

    function getAPI()
    {
        require_once 'inc/MCAPI.class.php';
        if (is_null($this->api)) {
            $this->api = new MCAPI($this->apikey);
        }
        return $this->api;
    }

    function getGroups()
    {

        // Get any existing copy of our transient data
        if (false === ($groups = get_transient('liahona_mc_groups'))) {
            // It wasn't there, so regenerate the data and save the transient
            $api = $this->getAPI();
            $groups = $api->listInterestGroupings($this->listId);
            set_transient('liahona_mc_groups', $groups, 60 * 60 * 24);
        }

        return $groups;
    }


    // Add Meta to comments
    function add_comment_meta($comment_id)
    {
        extract($_POST);
        $groupings = array();

        // options come in the form mc_options[group_id]=>array(opt1,opt2,opt3) ... need to
        // turn in to groups[group_id]=>"opt1,opt2,opt3"
        if(isset($mc_options))
            foreach($mc_options as $group_id => $group_names) {
                $groupings[]= array('id' => $group_id, 'groups' => implode(",",$group_names));
            }
        $options = array(
            'mc_subscribe' => isset($mc_subscribe) ? $mc_subscribe : '',
            'groupings' => $groupings
        );

        add_comment_meta(
            $comment_id,
            'mailchimp_options',
            $options,
            true
        );

    }

    // Add subscription when the comment is approved
    function subscription_add($cid, $comment)
    {
        $cid = (int)$cid;

        if (!is_object($comment))
            $comment = get_comment($cid);

        if ($comment->comment_karma == 0) {
            $options = get_comment_meta($cid, 'mailchimp_options', true);
            if ($options) {
                $subscribe = $options['mc_subscribe'];
                $groupings = $options['groupings'];
                if ($subscribe == 'on') {
                    $api = $this->getAPI();
                    $list_id = $this->listId;
                    $name = explode(" ", $comment->comment_author);
                    if (count($name) > 1) {
                        $lname = array_pop($name);
                        $fname = implode(" ", $name);
                    } else {
                        $lname = "";
                        $fname = $name[0];
                    }
                }
                $merge_vars = array(
                    'FNAME' => $fname,
                    'LNAME' => $lname,
                    'OPTIN_IP' => $comment->comment_author_IP,
                    'GROUPINGS' => $groupings
                );

                if ($api->listSubscribe($list_id, strtolower($comment->comment_author_email), $merge_vars) === true) {
                    // It worked!
                    update_comment_meta($cid, 'mailchimp_subscribe', 'subscribed', 'on');
//                    wp_mail(get_option('site_admin'), "New Subscription to email list via comments",
                    wp_mail('jloosli@gmail.com', "New Subscription to email list via comments",
                        "Subscriber: $comment->comment_author ($comment->comment_author_email)");
                } else {
                    // An error ocurred, send error message
                    // Commented out since errors mainly happened from people already being on the list
//                    mail('jloosli@gmail.com', "Error subscribing to mailchimp", 'Error: ' . $api->errorMessage);
                }
            }
        }
    }

}

$mc_liahona = new MC_LIAHONA();

function mc_comment_form() {
    ?>
    <div class='mc_comment_signup'>
        <input type="checkbox" name="mc_subscribe" id="mc_subscribe" value="on" />
        <label for="mc_subscribe">Please notify me of articles</label>
        <div class="mc_optional">
            <?php if(!isset($mc_liahona)) $mc_liahona = new MC_LIAHONA(); ?>
        <?php foreach($mc_liahona->getGroups() as $grp) : ?>
            <fieldset>
                <legend><?php echo $grp['name']; ?> <small>(optional)</small></legend>
                <ul>
                    <?php foreach($grp['groups'] as $item_no => $item): ?>
                    <li>
                        <input type='checkbox' name="grp_<?php echo $grp['id']."_".$item['name']; ?>"
                               id="grp_<?php echo $grp['id']."_".$item['name']; ?>"
                               value="<?php echo $item['name']; ?>" />
                        <label for="grp_<?php echo $grp['id']."_".$item['name']; ?>"><?php echo $item['name']; ?></label>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </fieldset>
        <?php endforeach; ?>
        </div>
    </div>
        <script>
            jQuery('#mc_subscribe').change(function() {
                if(this.checked) {
                    $(".mc_optional").show('slow');
                } else {
                    $(".mc_optional").hide("slow");
                }
            });
        </script>
        <?php

}