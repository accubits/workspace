import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivitystreamRoutingModule } from './activitystream-routing.module';
import { ActivitystreamComponent } from './activitystream.component';
import { ActivitySandboxService } from './activity.sandbox'
import { GreetingWidgetComponent } from './greeting-widget/greeting-widget.component';
import { CalendarWidgetComponent } from './calendar-widget/calendar-widget.component';
import { DashLeftComponent } from './dash-left/dash-left.component';
import { DashRightComponent } from './dash-right/dash-right.component';
import { TaskWidgetComponent } from './task-widget/task-widget.component';
import { ActivityStreamPanelComponent } from './activity-stream-panel/activity-stream-panel.component';
import { WidgetCreationComponent } from './widget-creation/widget-creation.component';
import { AsNavComponent } from './as-nav/as-nav.component';
import { AsRecentComponent } from './as-recent/as-recent.component';
import { AsTopComponent } from './as-top/as-top.component';
import { AsAnnouncementComponent } from './as-announcement/as-announcement.component';
import { AsTaskComponent } from './as-task/as-task.component';
import { AsWorkflowComponent } from './as-workflow/as-workflow.component';
import { SecMessageComponent } from './sec-message/sec-message.component';
import { SecPollComponent } from './sec-poll/sec-poll.component';
import { SecEventComponent } from './sec-event/sec-event.component';
import { SecAppreciationComponent } from './sec-appreciation/sec-appreciation.component';
import { SecAnnouncementComponent } from './sec-announcement/sec-announcement.component';
import { SecTaskComponent } from './sec-task/sec-task.component';
import { SecNewEmpComponent } from './sec-new-emp/sec-new-emp.component';
import { SecReportmodComponent } from './sec-reportmod/sec-reportmod.component';
import { BirthdayWidgetComponent } from './birthday-widget/birthday-widget.component';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { EditorComponent } from './editor/editor.component';
import { CKEditorModule } from 'ngx-ckeditor';
import { TaskDataService } from '../shared/services/task-data.service'
import { TaskSandbox } from '../task/task.sandbox';
import { SharedModule } from '../shared/shared.module';
import { CreateTaskpopComponent } from './create-taskpop/create-taskpop.component';
import { SecFormComponent } from './sec-form/sec-form.component';
import { AsListingComponent } from './as-listing/as-listing.component';
import { ActEventViewComponent } from './act-event-view/act-event-view.component';
import { TaskCarouselWidgetComponent } from './task-carousel-widget/task-carousel-widget.component';
import { InfiniteScrollModule } from 'ngx-infinite-scroll';

@NgModule({
  imports: [
    CommonModule,
    ActivitystreamRoutingModule,
    PerfectScrollbarModule,
    CKEditorModule,
    SharedModule,
    InfiniteScrollModule
  ],
  providers: [ActivitySandboxService,TaskDataService,TaskSandbox],
  declarations: [ActivitystreamComponent, GreetingWidgetComponent, CalendarWidgetComponent, DashLeftComponent, DashRightComponent, TaskWidgetComponent, ActivityStreamPanelComponent, WidgetCreationComponent, AsNavComponent, AsRecentComponent, AsTopComponent, AsAnnouncementComponent, AsTaskComponent, AsWorkflowComponent, SecMessageComponent, SecPollComponent, SecEventComponent, SecAppreciationComponent, SecAnnouncementComponent, SecTaskComponent, SecNewEmpComponent, SecReportmodComponent, BirthdayWidgetComponent, EditorComponent, CreateTaskpopComponent, SecFormComponent, AsListingComponent, ActEventViewComponent, TaskCarouselWidgetComponent]
})
export class ActivitystreamModule { }
