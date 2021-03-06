<?php

use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Dev\SapphireTest;

/**
 * @package siteconfig
 * @subpackage tests
 */
class SiteConfigTest extends SapphireTest
{
    protected static $fixture_file = 'SiteConfigTest.yml';

    protected static $illegal_extensions = array(
        'SilverStripe\\CMS\\Model\\SiteTree' => array('SiteTreeSubsites'),
    );

    public function testCanCreateRootPages()
    {
        $config = $this->objFromFixture('SilverStripe\\SiteConfig\\SiteConfig', 'default');

        // Log in without pages admin access
        $this->logInWithPermission('CMS_ACCESS_AssetAdmin');
        $this->assertFalse($config->canCreateTopLevel());

        // Login with necessary edit permission
        $perms = SiteConfig::config()->required_permission;
        $this->logInWithPermission(reset($perms));
        $this->assertTrue($config->canCreateTopLevel());
    }

    public function testCanViewPages()
    {
        $config = $this->objFromFixture('SilverStripe\\SiteConfig\\SiteConfig', 'default');
        $this->assertTrue($config->canViewPages());
    }

    public function testCanEdit()
    {
        $config = $this->objFromFixture('SilverStripe\\SiteConfig\\SiteConfig', 'default');

        // Unrelated permissions don't allow siteconfig
        $this->logInWithPermission('CMS_ACCESS_AssetAdmin');
        $this->assertFalse($config->canEdit());

        // Only those with edit permission can do this
        $this->logInWithPermission('EDIT_SITECONFIG');
        $this->assertTrue($config->canEdit());
    }

    public function testCanEditPages()
    {
        $config = $this->objFromFixture('SilverStripe\\SiteConfig\\SiteConfig', 'default');

        // Log in without pages admin access
        $this->logInWithPermission('CMS_ACCESS_AssetAdmin');
        $this->assertFalse($config->canEditPages());

        // Login with necessary edit permission
        $perms = SiteConfig::config()->required_permission;
        $this->logInWithPermission(reset($perms));
        $this->assertTrue($config->canEditPages());
    }
}
