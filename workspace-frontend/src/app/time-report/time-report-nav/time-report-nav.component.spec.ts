import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TimeReportNavComponent } from './time-report-nav.component';

describe('TimeReportNavComponent', () => {
  let component: TimeReportNavComponent;
  let fixture: ComponentFixture<TimeReportNavComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TimeReportNavComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TimeReportNavComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
