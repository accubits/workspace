import { CrmDataService } from './services/crm-data.service';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {NgxPaginationModule} from 'ngx-pagination';
//import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { MenuToggle } from './shared-logics';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AngularDateTimePickerModule } from 'angular2-datetimepicker';
import { TimeDateService } from './services/time-date.service';
import { TaskDataService } from './services/task-data.service';
import { TaskApiService } from './services/task-api.service';
import { TaskUtilityService } from './services/task-utility.service';
import { PartnerUtilityService} from './services/partner-utility.service';
import { TaskPostDataService } from './services/task-post-data.service';
import { UtilityService } from './services/utility.service';
import { PopupOverlayComponent } from './popup-overlay/popup-overlay.component';
import { OwlDateTimeModule,DateTimeAdapter, OwlNativeDateTimeModule,OWL_DATE_TIME_LOCALE,OWL_DATE_TIME_FORMATS } from 'ng-pick-datetime';
import { ObjectIteratePipe } from './pipes/object-iterate.pipe';
import { SortablejsModule } from 'angular-sortablejs/dist';
import { DriveDataService } from './services/drive-data.service';
import { DriveApiService } from './services/drive-api.service';
import { FilesizePipe } from './pipes/filesize.pipe';
import { CapitalizePipe } from './pipes/capitalize.pipe';
import { SettingsDataService } from './services/settings-data.service';
import { SettingsApiService } from './services/settings-api.service';
import { DataService } from './services/data.service';
import { FormsApiService } from './services/forms-api.service';
import { ToastService } from './services/toast.service';
import { FormsUtilityService } from './services/forms-utility.service';
import { ImageextensionPipe } from './pipes/imageextension.pipe';
import { ChatDataService } from './services/chat-data.service';
import { TarDataService } from './services/tar-data.service';
import { ActStreamDataService } from './services/act-stream-data.service';
import { ActivityApiService } from './services/activity-api.service';
import { HrmApiService } from './services/hrm-api.service';
import { FilesizeMBPipe } from './pipes/filesize-mb.pipe';
import { PartnerDataService} from './services/partner-data.service';
import { PartnerApiService} from './services/partner-api.service';
import { PartnerManagerDataService } from './services/partner-manager-data.service';
import { PartnerManagerApiService } from './services/partner-manager-api.service';
import { CalendarDataService} from './services/calendar-data.service';
import { CalenderApiService } from './services/calender-api.service';
import { TimeReportDataService } from './services/time-report-data.service';
import { TimeReportApiService } from './services/time-report-api.service';
import { ClockDataService } from './services/clock.data.service';
import { ClockApiService } from './services/clock-api.service';
import { CountdownModule } from 'ngx-countdown';
import { CdTimerModule } from 'angular-cd-timer';
import { ScrollToModule } from '@nicky-lenaers/ngx-scroll-to';
import { SuperAdminDataService} from './services/super-admin-data.service';
import { SuperAdminApiService} from './services/super-admin-api.service';
import { HrmDataService} from './services/hrm-data.service';
import { HrmUtilityService } from './services/hrm-utility.service';
import { DragScrollModule } from 'ngx-drag-scroll';

// import {HeaderService} from './header.service'

import * as _moment from 'moment';
import { MomentDateTimeAdapter } from 'ng-pick-datetime-moment';

// export const  MY_CUSTOM_FORMATS = {
//   fullPickerInput: {year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric'},
//   datePickerInput: {year: 'numeric', month: 'short', day: 'numeric'},
//   timePickerInput: {hour: 'numeric', minute: 'numeric'},
//   monthYearLabel: {year: 'numeric', month: 'short'},
//   dateA11yLabel: {year: 'numeric', month: 'long', day: 'numeric'},
//   monthYearA11yLabel: {year: 'numeric', month: 'long'},
// };


const moment = (_moment as any).default ? (_moment as any).default : _moment;

export const MY_CUSTOM_FORMATS = {
    parseInput: 'LL LT',
    fullPickerInput: 'll LT',
    datePickerInput: 'll',
    timePickerInput: 'LT',
    monthYearLabel: 'MMM YYYY',
    dateA11yLabel: 'LL',
    monthYearA11yLabel: 'MMMM YYYY',
};

// export const MY_CUSTOM_FORMATS = {
//   parseInput: 'LL LT',
//   fullPickerInput: 'LL LT',
//   datePickerInput: 'LL',
//   timePickerInput: 'LT',
//   monthYearLabel: 'MMM YYYY',
//   dateA11yLabel: 'LL',
//   monthYearA11yLabel: 'MMMM YYYY',
// };


@NgModule({
  imports: [
    CommonModule,
    //BrowserModule,
    ReactiveFormsModule,
    FormsModule,
    AngularDateTimePickerModule,
    SortablejsModule.forRoot({ animation: 150 }),
    ScrollToModule.forRoot(),
    OwlDateTimeModule,
    OwlNativeDateTimeModule,
    NgxPaginationModule,
    DragScrollModule,

  ],
  declarations: [PopupOverlayComponent, ObjectIteratePipe, FilesizePipe, CapitalizePipe,ImageextensionPipe, FilesizeMBPipe],
  exports: [AngularDateTimePickerModule, DragScrollModule ,CdTimerModule,CountdownModule,ScrollToModule,NgxPaginationModule,FormsModule, PopupOverlayComponent, OwlDateTimeModule, OwlNativeDateTimeModule, SortablejsModule, ObjectIteratePipe,FilesizePipe,CapitalizePipe,ImageextensionPipe,FilesizeMBPipe],
  providers: [
    TimeDateService,
    MenuToggle,
    TaskDataService,
    TaskApiService,
    DriveDataService,
    DriveApiService,
    TaskPostDataService,
    TaskUtilityService,
    HrmUtilityService,
    UtilityService,
    SettingsDataService,
    SettingsApiService,
    DataService,
    FormsApiService,
    ToastService,
    FormsUtilityService,
    ImageextensionPipe,
    ChatDataService,
    TarDataService,
    ActStreamDataService,
    ActivityApiService,
    HrmApiService,
    PartnerDataService,
    PartnerManagerDataService,
    PartnerManagerApiService,
    TimeReportDataService,
    PartnerApiService,
    PartnerUtilityService,
    ClockDataService,
    ClockApiService,
    TimeReportApiService,
    CalendarDataService,
    CalenderApiService,
    SuperAdminDataService,
    SuperAdminApiService,
    HrmDataService,
    CrmDataService,

  //   {provide: OWL_DATE_TIME_LOCALE, useValue: 'en-DK'},
  //  {provide: OWL_DATE_TIME_FORMATS, useValue: MY_CUSTOM_FORMATS }
  {provide: DateTimeAdapter, useClass: MomentDateTimeAdapter, deps: [OWL_DATE_TIME_LOCALE]},

  {provide: OWL_DATE_TIME_FORMATS, useValue: MY_CUSTOM_FORMATS},
]


})
export class SharedModule { }

