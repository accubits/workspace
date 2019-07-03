import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from '../shared/shared.module';
import { SuperAdminRoutingModule } from './super-admin-routing.module';
import { SuperAdminComponent } from './super-admin.component';
import { HeaderComponent } from './header/header.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { LicenseHeaderComponent } from './license-header/license-header.component';
import { LicenseContentComponent } from './license-content/license-content.component';
import { LicenseComponent } from './license/license.component';
import { LicenseDetailsPopComponent } from './license-details-pop/license-details-pop.component';
import { CreateLicenseComponent } from './create-license/create-license.component';
import { MoreOptionComponent } from './more-option/more-option.component';
import { VerticalComponent } from './vertical/vertical.component';
import { CountryComponent } from './country/country.component';
import { SuperAdminSandbox } from './super-admin.sandbox';
import { RequestTableComponent } from './request-table/request-table.component';
import { ExpiredTableComponent } from './expired-table/expired-table.component';
import { CreateCountryComponent } from './create-country/create-country.component';
import { CreateVerticalComponent } from './create-vertical/create-vertical.component';
import { ExpiredFilterComponent } from './expired-filter/expired-filter.component';
import { ExpiredLicenseDetailComponent } from './expired-license-detail/expired-license-detail.component';
import { ExpiredDetailTabComponent } from './expired-detail-tab/expired-detail-tab.component';
import { ExpiredHistoryTabComponent } from './expired-history-tab/expired-history-tab.component';
import { AwaitingActivationComponent } from './awaiting-activation/awaiting-activation.component';
import { AwaitingFilterComponent } from './awaiting-filter/awaiting-filter.component';
import { AwaitingDetailComponent } from './awaiting-detail/awaiting-detail.component';
import { AwaitingCurrentDetailComponent } from './awaiting-current-detail/awaiting-current-detail.component';
import { LicenseFilterComponent } from './license-filter/license-filter.component';
import { RequestDetailPopComponent } from './request-detail-pop/request-detail-pop.component';
import { RequestFilterComponent } from './request-filter/request-filter.component';
import { AwaitingHistoryComponent } from './awaiting-history/awaiting-history.component';
import { PartnerComponent } from './partner/partner.component';
import { PartnerHeadComponent } from './partner-head/partner-head.component';
import { PartnerNavComponent } from './partner-nav/partner-nav.component';
import { PartnerTableComponent } from './partner-table/partner-table.component';
import { SubAdminComponent } from './sub-admin/sub-admin.component';
import { SubAdminHeadComponent } from './sub-admin-head/sub-admin-head.component';
import { SubAdminNavComponent } from './sub-admin-nav/sub-admin-nav.component';
import { SubAdminTableComponent } from './sub-admin-table/sub-admin-table.component';
import { AddSubAdminComponent } from './add-sub-admin/add-sub-admin.component';
import { PermissionsPopupComponent } from './permissions-popup/permissions-popup.component';
import { SubAdminDetailComponent } from './sub-admin-detail/sub-admin-detail.component';
import { SubAdminDetailPartnerComponent } from './sub-admin-detail-partner/sub-admin-detail-partner.component';
import { SettingsComponent } from './settings/settings.component';
import { SettingsDashboardComponent } from './settings-dashboard/settings-dashboard.component';
import { ProfileComponent } from './profile/profile.component';
import { TwoFactorComponent } from './two-factor/two-factor.component';
import { ChangePasswordComponent } from './change-password/change-password.component';
import { EditProfileComponent } from './edit-profile/edit-profile.component';
import { OrganizationComponent } from './organization/organization.component';
import { OrgDetailPopComponent } from './org-detail-pop/org-detail-pop.component';
import { FormsComponent } from './forms/forms.component';
import { FormPublishedComponent } from './form-published/form-published.component';
import { FormDraftComponent } from './form-draft/form-draft.component';
import { FormInactiveComponent } from './form-inactive/form-inactive.component';
import { FormHeaderComponent } from './form-header/form-header.component';
import { FormShareComponent } from './form-share/form-share.component';
import { AddPartnerComponent } from './add-partner/add-partner.component';
import { CreateFormComponent } from './create-form/create-form.component';
import { PublishPopComponent } from './publish-pop/publish-pop.component';


@NgModule({
  imports: [
    CommonModule,
    SuperAdminRoutingModule,
    SharedModule,
    PerfectScrollbarModule
  ],
 
  declarations: [SuperAdminComponent, HeaderComponent, SidebarComponent, LicenseHeaderComponent, LicenseContentComponent, LicenseComponent, LicenseDetailsPopComponent, CreateLicenseComponent, MoreOptionComponent, VerticalComponent, CountryComponent, RequestTableComponent, ExpiredTableComponent, CreateCountryComponent, CreateVerticalComponent, ExpiredFilterComponent, ExpiredLicenseDetailComponent, ExpiredDetailTabComponent, ExpiredHistoryTabComponent, AwaitingActivationComponent, AwaitingFilterComponent, AwaitingDetailComponent, AwaitingCurrentDetailComponent, LicenseFilterComponent, RequestDetailPopComponent, RequestFilterComponent, AwaitingHistoryComponent, OrganizationComponent, OrgDetailPopComponent, FormsComponent, FormPublishedComponent, FormDraftComponent, FormInactiveComponent, FormHeaderComponent, FormShareComponent, CreateFormComponent,  PartnerComponent, PartnerHeadComponent, PartnerNavComponent, PartnerTableComponent, SubAdminComponent, SubAdminHeadComponent, SubAdminNavComponent, SubAdminTableComponent, AddSubAdminComponent, PermissionsPopupComponent, SubAdminDetailComponent, SubAdminDetailPartnerComponent, SettingsComponent, SettingsDashboardComponent, ProfileComponent, TwoFactorComponent, ChangePasswordComponent, EditProfileComponent,AddPartnerComponent,PublishPopComponent],
  providers: [SuperAdminSandbox]
})
export class SuperAdminModule { }
