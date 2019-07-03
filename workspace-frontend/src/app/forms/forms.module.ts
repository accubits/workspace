import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DndListModule } from 'ngx-drag-and-drop-lists';
import { FormsRoutingModule } from './forms-routing.module';
import { FormsComponent } from './forms.component';
import { FormWrapLeftComponent } from './form-wrap-left/form-wrap-left.component';
import { ContentWrapRightComponent } from './content-wrap-right/content-wrap-right.component';
import { FormElementsModule } from '../shared/form-elements/form_elements.module';
import { DataService } from '../shared/services/data.service';
import { FormAdminmodalNavComponent } from './form-adminmodal-nav/form-adminmodal-nav.component';
import { FormOverviewComponent } from './form-overview/form-overview.component';
import { FormIndiResponseComponent } from './form-indi-response/form-indi-response.component';
import { FormListingWrapComponent } from './form-listing-wrap/form-listing-wrap.component';
import { FormCreationComponent } from './form-creation/form-creation.component';
import { NewFormComponent } from './new-form/new-form.component';
import { FormListingNavComponent } from './form-listing-nav/form-listing-nav.component';
import { SharedWithMeComponent } from './shared-with-me/shared-with-me.component';
import { DraftsComponent } from './drafts/drafts.component';
import { ShareOptionComponent } from './share-option/share-option.component';
import { MoreOptionComponent } from './more-option/more-option.component';
import { PublishedComponent } from './published/published.component';
import { FooterOptionComponent } from './footer-option/footer-option.component';
import { FormPreviewComponent } from './form-preview/form-preview.component';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { FormsSandbox } from './forms.sandbox';
import { SharedModule } from '../shared/shared.module';
import { InactiveComponent } from './inactive/inactive.component';
import { PublishFormComponent } from './publish-form/publish-form.component';
import { FormSubmitModalComponent } from './form-submit-modal/form-submit-modal.component';
import { FormPreviewOnlyComponent } from './form-preview-only/form-preview-only.component';
import { SendToComponent } from './send-to/send-to.component';


@NgModule({
  imports: [
    FormsRoutingModule,
    CommonModule,
    DndListModule,
    FormElementsModule,
    PerfectScrollbarModule,
    SharedModule
  ],
  declarations: [
    FormsComponent,
    FormWrapLeftComponent,
    ContentWrapRightComponent,
    FormAdminmodalNavComponent,
    FormOverviewComponent,
    FormIndiResponseComponent,
    FormListingWrapComponent,
    FormCreationComponent,
    NewFormComponent,
    FormListingNavComponent,
    SharedWithMeComponent,
    DraftsComponent,
    ShareOptionComponent,
    MoreOptionComponent,
    PublishedComponent,
    FooterOptionComponent,
    FormPreviewComponent,
    InactiveComponent,
    PublishFormComponent,
    FormSubmitModalComponent,
    FormPreviewOnlyComponent,
    SendToComponent
  ],
  providers: [FormsSandbox],
  exports:[
    NewFormComponent,
    FormCreationComponent
  ]
})
export class FormsModule { }
