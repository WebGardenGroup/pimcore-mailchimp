pimcore.registerNS('pimcore.plugin.WgMailchimpBundle')

pimcore.plugin.WgMailchimpBundle = Class.create(pimcore.plugin.admin, {
  getClassName: function () {
    return 'pimcore.plugin.WgMailchimpBundle'
  },

  initialize: function () {
    pimcore.plugin.broker.registerPlugin(this)
  },

  pimcoreReady: function () {
    if (pimcore.currentuser.permissions.indexOf('mailchimp.permission') >= 0) {
      const layoutToolbar = pimcore.globalmanager.get('layout_toolbar')
      if (layoutToolbar.settingsMenu) {
        layoutToolbar.settingsMenu.add(Ext.create('Ext.Action', {
          id: 'mailchimp_settings_button',
          text: t('mailchimp.settings.title'),
          iconCls: 'pimcore_icon_mailchimp',
          handler: this.openSettings.bind(this)
        }))
      }
    }
  },
  openSettings: function () {
    try {
      pimcore.globalmanager.get('wg_mailchimp_settings').activate()
    } catch (e) {
      pimcore.globalmanager.add('wg_mailchimp_settings', new pimcore.plugin.WgMailchimpBundle.Settings())
    }
  }
})

// eslint-disable-next-line no-new
new pimcore.plugin.WgMailchimpBundle()
