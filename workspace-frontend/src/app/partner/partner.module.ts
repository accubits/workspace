import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PartnerRoutingModule } from './partner-routing.module';
import { PartnerComponent } from './partner.component';
//import { HeaderComponent } from './header/header.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { OrganisationComponent } from './organisation/organisation.component';
import { FooterComponent } from './footer/footer.component';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { CreateOrgComponent } from './create-org/create-org.component';
import { AllOrgHeaderComponent } from './all-org-header/all-org-header.component';
import { OrgTableHeadComponent } from './org-table-head/org-table-head.component';
import { OrgTableComponent } from './org-table/org-table.component';
import { OrgPopupComponent } from './org-popup/org-popup.component';
import { DetailsContentComponent } from './details-content/details-content.component';
import { LicenseContentComponent } from './license-content/license-content.component';
import { LicenseComponent } from './license/license.component';
import { LicenseHeaderComponent } from './license-header/license-header.component';
import { LicenseTableHeadComponent } from './license-table-head/license-table-head.component';
import { LicenseTableComponent } from './license-table/license-table.component';
import { LicensePopComponent } from './license-pop/license-pop.component';
import { RequestTableComponent } from './request-table/request-table.component';
import { ExpiredTableComponent } from './expired-table/expired-table.component';
import { RequestTableHeadComponent } from './request-table-head/request-table-head.component';
import { ExpiredTableHeadComponent } from './expired-table-head/expired-table-head.component';
import { AdminTableHeadComponent } from './admin-table-head/admin-table-head.component';
import { AdminTableComponent } from './admin-table/admin-table.component';
import { RequestPopComponent } from './request-pop/request-pop.component';
import { CreateLicenseComponent } from './create-license/create-license.component';
import { SharedModule } from '../shared/shared.module';
import {PartnerSandbox } from './partner.sandbox';
import {SettingsSandbox} from '../settings/settings.sandbox';

import { EditOrgComponent } from './edit-org/edit-org.component';
import { EditLicenseComponent } from './edit-license/edit-license.component';
import { DataService } from '../shared/services/data.service';
import { HeaderComponent } from './header/header.component';
import { LicenseRequestPopComponent } from './license-request-pop/license-request-pop.component';
import { DeletePopComponent } from './delete-pop/delete-pop.component';
// import { PartnerManagerComponent } from './partner-manager/partner-manager.component';
import { MoreOptionComponent } from './more-option/more-option.component';
import { UnlicenseTableComponent } from './unlicense-table/unlicense-table.component';
import { RenewLicensePopComponent } from './renew-license-pop/renew-license-pop.component';
import { PartnerSettingsComponent } from './partner-settings/partner-settings.component';
import { SettingsHeadComponent } from './settings-head/settings-head.component';
import { ProfileComponent } from './profile/profile.component';
import { TwoFactorComponent } from './two-factor/two-factor.component';
import { EditProfileComponent } from './edit-profile/edit-profile.component';
import { ChangePasswordComponent } from './change-password/change-password.component';
import { CreateOrgLicenseComponent } from './create-org-license/create-org-license.component';
import { MoreOptionLicenseComponent } from './more-option-license/more-option-license.component';
import { LicensedOrgTableComponent } from './licensed-org-table/licensed-org-table.component';
import { DeleteConfirmComponent } from './delete-confirm/delete-confirm.component';
import { LicenseContentUpdatedComponent } from './license-content-updated/license-content-updated.component';
import { DeleteOrgConfirmComponent } from './delete-org-confirm/delete-org-confirm.component';
import { ApprovedTableComponent } from './approved-table/approved-table.component';
import { ApprovedPopComponent } from './approved-pop/approved-pop.component';
import { DeleteOrgPopComponent } from './delete-org-pop/delete-org-pop.component';
import { ApplyConfirmComponent } from './apply-confirm/apply-confirm.component';
import { RenewPopComponent } from './renew-pop/renew-pop.component';
import { SettingsComponent } from './settings/settings.component';
import { DashboardSettingsComponent } from './dashboard-settings/dashboard-settings.component';


@NgModule({
  imports: [
    CommonModule,
    PartnerRoutingModule,
    PerfectScrollbarModule,
    SharedModule
  ],
  providers: [PartnerSandbox, SettingsSandbox],
  declarations: [ PartnerComponent, SidebarComponent, OrganisationComponent, FooterComponent, CreateOrgComponent, AllOrgHeaderComponent, OrgTableHeadComponent, OrgTableComponent, OrgPopupComponent, DetailsContentComponent, LicenseContentComponent, LicenseComponent, LicenseHeaderComponent, LicenseTableHeadComponent, LicenseTableComponent, LicensePopComponent, RequestTableComponent, ExpiredTableComponent, RequestTableHeadComponent, ExpiredTableHeadComponent, AdminTableHeadComponent, AdminTableComponent, RequestPopComponent, CreateLicenseComponent, EditOrgComponent,EditLicenseComponent, HeaderComponent, LicenseRequestPopComponent, DeletePopComponent, MoreOptionComponent, UnlicenseTableComponent, RenewLicensePopComponent,SettingsHeadComponent,PartnerSettingsComponent,ProfileComponent,TwoFactorComponent,EditProfileComponent,ChangePasswordComponent, CreateOrgLicenseComponent, MoreOptionLicenseComponent, LicensedOrgTableComponent,DeleteConfirmComponent, LicenseContentUpdatedComponent, DeleteOrgConfirmComponent, ApprovedTableComponent, ApprovedPopComponent, DeleteOrgPopComponent, ApplyConfirmComponent, RenewPopComponent, SettingsComponent, DashboardSettingsComponent]
})
export class PartnerModule { }
