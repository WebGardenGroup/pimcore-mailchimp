pimcore.registerNS('pimcore.plugin.WgMailchimpBundle.settings')

pimcore.plugin.WgMailchimpBundle.Settings = Class.create(pimcore.plugin.admin, {
  initialize: function () {
    this.getData()
  },
  getData: function () {
    Ext.Ajax.request({
      url: Routing.generate('wg_mailchimp_admin_getsettings'),
      success: function (response) {
        this.values = Ext.decode(response.responseText)

        this.getTabPanel()
      }.bind(this)
    })
  },

  getTabPanel: function () {
    const config = this.values.config

    if (!this.panel) {
      this.panel = Ext.create('Ext.panel.Panel', {
        id: 'mailchimp_settings',
        title: t('mailchimp.settings.title'),
        iconCls: 'pimcore_icon_mailchimp_color',
        border: false,
        layout: 'fit',
        closable: true
      })

      this.panel.on('destroy', function () {
        pimcore.globalmanager.remove('wg_mailchimp_settings')
      })

      const listIds = []

      if (config.list_id) {
        for (let i = 0; i < config.list_id.length; i++) {
          listIds.push({
            fieldLabel: 'Audience ID',
            name: 'config.list_id',
            xtype: 'textfield',
            value: config.list_id[i],
            width: 600
          })
        }
      }

      listIds.push({
        xtype: 'displayfield',
        hideLabel: true,
        width: 600,
        value: t('mailchimp.settings.help.list_id'),
        cls: 'pimcore_extra_label_bottom'
      })

      listIds.push({
        xtype: 'button',
        text: 'Add',
        handler: function (button) {
          const fieldset = button.up('fieldset')
          fieldset.insert(fieldset.items.length - 2, {
            fieldLabel: 'Audience ID',
            name: 'config.list_id',
            xtype: 'textfield',
            width: 600
          })
        }
      })

      this.layout = Ext.create('Ext.form.Panel', {
        bodyStyle: 'padding:20px 20px 20px 20px;',
        title: t('mailchimp.settings.title'),
        border: false,
        autoScroll: true,
        forceLayout: true,
        defaults: {
          forceLayout: true
        },
        fieldDefaults: {
          labelWidth: 250
        },
        buttons: [
          {
            text: t('mailchimp.validate_settings'),
            handler: this.validateSettings.bind(this),
            iconCls: 'pimcore_icon_inspect'
          },
          {
            text: t('save'),
            handler: this.save.bind(this),
            iconCls: 'pimcore_icon_apply'
          }
        ],
        items: [
          {
            fieldLabel: 'API Key',
            xtype: 'textfield',
            name: 'config.api_key',
            value: config.api_key,
            width: 600,
            allowBlank: false
          },
          {
            xtype: 'displayfield',
            hideLabel: true,
            width: 600,
            value: t('mailchimp.settings.help.api_key'),
            cls: 'pimcore_extra_label_bottom'
          },
          {
            fieldLabel: 'Server Prefix',
            xtype: 'textfield',
            name: 'config.server_prefix',
            value: config.server_prefix,
            width: 600,
            allowBlank: false
          },
          {
            xtype: 'displayfield',
            hideLabel: true,
            width: 600,
            value: t('mailchimp.settings.help.server_prefix')
          },
          {
            title: 'Audience ID(s)',
            xtype: 'fieldset',
            name: 'config.list_id',
            items: listIds
          }
        ]
      })

      this.panel.add(this.layout)

      const tabPanel = Ext.getCmp('pimcore_panel_tabs')
      tabPanel.add(this.panel)
      tabPanel.setActiveItem(this.panel)

      pimcore.layout.refresh()
    }

    return this.panel
  },

  activate: function () {
    const tabPanel = Ext.getCmp('pimcore_panel_tabs')
    tabPanel.setActiveItem('mailchimp_settings')
  },

  save: function () {
    const values = this.layout.getForm().getFieldValues()

    Ext.Ajax.request({
      url: Routing.generate('wg_mailchimp_admin_savesettings'),
      method: 'PUT',
      params: {
        data: Ext.encode(values)
      },
      success: function (response) {
        try {
          const res = Ext.decode(response.responseText)
          if (res.success) {
            pimcore.helpers.showNotification(t('success'), t('saved_successfully'), 'success')
          } else {
            pimcore.helpers.showNotification(t('error'), t('error_general'),
              'error', t(res.message))
          }
        } catch (e) {
          pimcore.helpers.showNotification(t('error'), t('error_general'), 'error')
        }
      }
    })
  },

  validateSettings: function () {
    const values = this.layout.getForm().getFieldValues()

    Ext.Ajax.request({
      url: Routing.generate('wg_mailchimp_admin_validatesettings'),
      method: 'POST',
      params: {
        data: Ext.encode(values)
      },
      success: function (response) {
        console.log(response)
        pimcore.helpers.showNotification(t('success'), t('mailchimp.validation_successful'), 'success')
      },
      failure: function (response) {
        pimcore.helpers.showNotification(t('error'), t('error_general'), 'error', response.responseText)
      }
    })
  }
})
