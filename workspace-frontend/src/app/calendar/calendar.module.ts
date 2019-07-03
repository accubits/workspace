import { NgModule } from '@angular/core';
import { CommonModule} from '@angular/common';
import { CalendarRoutingModule } from './calendar-routing.module';
import { CalendarMainComponent } from './calendar-main/calendar-main.component';
import { CalendarComponent } from './calendar.component';
import { CalendarSidebarComponent } from './calendar-sidebar/calendar-sidebar.component';
import { SharedModule } from '../shared/shared.module';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { CalendarWeekComponent } from './calendar-week/calendar-week.component';
import { CalendarDayComponent } from './calendar-day/calendar-day.component';
import { EventViewComponent } from './event-view/event-view.component';
import { EventEndComponent } from './event-end/event-end.component';
import { FullCalendarModule } from 'ng-fullcalendar';
import { CreateNewComponent } from './create-new/create-new.component';
import { CKEditorModule } from 'ngx-ckeditor';
import { CalendarSandbox } from './calendar.sandbox';
import { CalendarNvbarComponent } from './calendar-nvbar/calendar-nvbar.component';
import { EditorComponent } from './editor/editor.component';
import { ActivitySandboxService } from '../activitystream/activity.sandbox';
//import { HeaderComponent } from '../authorized/header/header.component';
//import { SidebarComponent } from '../authorized/sidebar/sidebar.component';

@NgModule({
  imports: [
    CommonModule,
    CalendarRoutingModule,
    SharedModule,
    PerfectScrollbarModule,
    FullCalendarModule,
    CKEditorModule
    //HeaderComponent,
    //SidebarComponent
  ],
  providers:[CalendarSandbox,ActivitySandboxService],
  declarations: [CalendarComponent,CalendarMainComponent, CalendarSidebarComponent, CalendarWeekComponent, CalendarDayComponent, EventViewComponent, EventEndComponent, CreateNewComponent, CalendarNvbarComponent, EditorComponent]
})
export class CalendarModule { }
