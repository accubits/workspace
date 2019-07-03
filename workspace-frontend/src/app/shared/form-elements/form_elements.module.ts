import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { SingleLineComponent } from './single-line/single-line.component';
import { NumberComponent } from './number/number.component';
import { CheckboxesComponent } from './checkboxes/checkboxes.component';
import { MultipleChoicesComponent } from './multiple-choices/multiple-choices.component';
import { DropdownComponent } from './dropdown/dropdown.component';
import { ParagraphComponent } from './paragraph/paragraph.component';
import { SectionBreakComponent } from './section-break/section-break.component';
import { PageBreakComponent } from './page-break/page-break.component';
import { DndListModule } from 'ngx-drag-and-drop-lists';
import { NameComponent } from './name/name.component';
import { FileUploadComponent } from './file-upload/file-upload.component';
import { AddressComponent } from './address/address.component';
import { DateComponent } from './date/date.component';
import { EmailComponent } from './email/email.component';
import { TimeComponent } from './time/time.component';
import { WebsiteComponent } from './website/website.component';
import { PhoneComponent } from './phone/phone.component';
import { PriceComponent } from './price/price.component';
import { LikertComponent } from './likert/likert.component';
import { RatingComponent } from './rating/rating.component';
import { UserlistComponent } from './userlist/userlist.component';
import { FormComponentComponent } from './form-component.component';
import { AddressPreviewComponent } from './address-preview/address-preview.component';
import { CheckboxesPreviewComponent } from './checkboxes-preview/checkboxes-preview.component';
import { DatePreviewComponent } from './date-preview/date-preview.component';
import { DropdownPreviewComponent } from './dropdown-preview/dropdown-preview.component';
import { EmailPreviewComponent } from './email-preview/email-preview.component';
import { FileUploadPreviewComponent } from './file-upload-preview/file-upload-preview.component';
import { LikertPreviewComponent } from './likert-preview/likert-preview.component';
import { MultipleChoicePreviewComponent } from './multiple-choice-preview/multiple-choice-preview.component';
import { NamePreviewComponent } from './name-preview/name-preview.component';
import { NumberPreviewComponent } from './number-preview/number-preview.component';
import { ParagraphPreviewComponent } from './paragraph-preview/paragraph-preview.component';
import { PhonePreviewComponent } from './phone-preview/phone-preview.component';
import { PricePreviewComponent } from './price-preview/price-preview.component';
import { RatingPreviewComponent } from './rating-preview/rating-preview.component';
import { SectionBreakPreviewComponent } from './section-break-preview/section-break-preview.component';
import { SingleLinePreviewComponent } from './single-line-preview/single-line-preview.component';
import { TimePreviewComponent } from './time-preview/time-preview.component';
import { UserlistPreviewComponent } from './userlist-preview/userlist-preview.component';
import { WebsitePreviewComponent } from './website-preview/website-preview.component';
import { FormPreviewLoaderComponent } from './form-preview-loader/form-preview-loader.component';
import { PageBreakPreviewComponent } from './page-break-preview/page-break-preview.component';
import { AddressSubmitComponent } from './address-submit/address-submit.component';
import { CheckboxesSubmitComponent } from './checkboxes-submit/checkboxes-submit.component';
import { DateSubmitComponent } from './date-submit/date-submit.component';
import { DropdownSubmitComponent } from './dropdown-submit/dropdown-submit.component';
import { EmailSubmitComponent } from './email-submit/email-submit.component';
import { FileUploadSubmitComponent } from './file-upload-submit/file-upload-submit.component';
import { LikertSubmitComponent } from './likert-submit/likert-submit.component';
import { MultipleChoiceSubmitComponent } from './multiple-choice-submit/multiple-choice-submit.component';
import { NameSubmitComponent } from './name-submit/name-submit.component';
import { NumberSubmitComponent } from './number-submit/number-submit.component';
import { ParagraphSubmitComponent } from './paragraph-submit/paragraph-submit.component';
import { PhoneSubmitComponent } from './phone-submit/phone-submit.component';
import { PriceSubmitComponent } from './price-submit/price-submit.component';
import { RatingSubmitComponent } from './rating-submit/rating-submit.component';
import { SingleLineSubmitComponent } from './single-line-submit/single-line-submit.component';
import { TimeSubmitComponent } from './time-submit/time-submit.component';
import { UserlistSubmitComponent } from './userlist-submit/userlist-submit.component';
import { WebsiteSubmitComponent } from './website-submit/website-submit.component';
import { PageBreakSubmitComponent } from './page-break-submit/page-break-submit.component';
import { SectionBreakSubmitComponent } from './section-break-submit/section-break-submit.component';
import { FormSubmitLoaderComponent } from './form-submit-loader/form-submit-loader.component';
import { AddressResponseComponent } from './address-response/address-response.component';
import { CheckboxesResponseComponent } from './checkboxes-response/checkboxes-response.component';
import { DateResponseComponent } from './date-response/date-response.component';
import { DropdownResponseComponent } from './dropdown-response/dropdown-response.component';
import { EmailResponseComponent } from './email-response/email-response.component';
import { FileUploadResponseComponent } from './file-upload-response/file-upload-response.component';
import { LikertResponseComponent } from './likert-response/likert-response.component';
import { MultipleChoiceResponseComponent } from './multiple-choice-response/multiple-choice-response.component';
import { NameResponseComponent } from './name-response/name-response.component';
import { NumberResponseComponent } from './number-response/number-response.component';
import { ParagraphResponseComponent } from './paragraph-response/paragraph-response.component';
import { PhoneResponseComponent } from './phone-response/phone-response.component';
import { PriceResponseComponent } from './price-response/price-response.component';
import { RatingResponseComponent } from './rating-response/rating-response.component';
import { SingleLineResponseComponent } from './single-line-response/single-line-response.component';
import { TimeResponseComponent } from './time-response/time-response.component';
import { UserlistResponseComponent } from './userlist-response/userlist-response.component';
import { WebsiteResponseComponent } from './website-response/website-response.component';
import { FormResponseLoaderComponent } from './form-response-loader/form-response-loader.component';
import { SharedModule } from '../shared.module';
import { DeleteConfirmPopupComponent } from './delete-confirm-popup/delete-confirm-popup.component';


@NgModule({
  declarations: [
    SingleLineComponent,
    NumberComponent,
    CheckboxesComponent,
    MultipleChoicesComponent,
    DropdownComponent,
    ParagraphComponent,
    SectionBreakComponent,
    PageBreakComponent,
    NameComponent,
    FileUploadComponent,
    AddressComponent,
    DateComponent,
    EmailComponent,
    TimeComponent,
    WebsiteComponent,
    PhoneComponent,
    PriceComponent,
    LikertComponent,
    RatingComponent,
    UserlistComponent,
    FormComponentComponent,
    AddressPreviewComponent,
    CheckboxesPreviewComponent,
    DatePreviewComponent,
    DropdownPreviewComponent,
    EmailPreviewComponent,
    FileUploadPreviewComponent,
    LikertPreviewComponent,
    MultipleChoicePreviewComponent,
    NamePreviewComponent,
    NumberPreviewComponent,
    ParagraphPreviewComponent,
    PhonePreviewComponent,
    PricePreviewComponent,
    RatingPreviewComponent,
    SectionBreakPreviewComponent,
    SingleLinePreviewComponent,
    TimePreviewComponent,
    UserlistPreviewComponent,
    WebsitePreviewComponent,
    FormPreviewLoaderComponent,
    PageBreakPreviewComponent,
    AddressSubmitComponent,
    CheckboxesSubmitComponent,
    DateSubmitComponent,
    DropdownSubmitComponent,
    EmailSubmitComponent,
    FileUploadSubmitComponent,
    LikertSubmitComponent,
    MultipleChoiceSubmitComponent,
    NameSubmitComponent,
    NumberSubmitComponent,
    ParagraphSubmitComponent,
    PhoneSubmitComponent,
    PriceSubmitComponent,
    RatingSubmitComponent,
    SingleLineSubmitComponent,
    TimeSubmitComponent,
    UserlistSubmitComponent,
    WebsiteSubmitComponent,
    PageBreakSubmitComponent,
    SectionBreakSubmitComponent,
    FormSubmitLoaderComponent,
    AddressResponseComponent,
    CheckboxesResponseComponent,
    DateResponseComponent,
    DropdownResponseComponent,
    EmailResponseComponent,
    FileUploadResponseComponent,
    LikertResponseComponent,
    MultipleChoiceResponseComponent,
    NameResponseComponent,
    NumberResponseComponent,
    ParagraphResponseComponent,
    PhoneResponseComponent,
    PriceResponseComponent,
    RatingResponseComponent,
    SingleLineResponseComponent,
    TimeResponseComponent,
    UserlistResponseComponent,
    WebsiteResponseComponent,
    FormResponseLoaderComponent,
    DeleteConfirmPopupComponent
  ],
  imports: [
    CommonModule,
    DndListModule,
    FormsModule,
    SharedModule
  ],
  exports: [
    FormComponentComponent,
    FormPreviewLoaderComponent,
    FormSubmitLoaderComponent,
    FormResponseLoaderComponent
  ],
  providers: []
})


export class FormElementsModule { }

