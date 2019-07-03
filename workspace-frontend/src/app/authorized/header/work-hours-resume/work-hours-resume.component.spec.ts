import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkHoursResumeComponent } from './work-hours-resume.component';

describe('WorkHoursResumeComponent', () => {
  let component: WorkHoursResumeComponent;
  let fixture: ComponentFixture<WorkHoursResumeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WorkHoursResumeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WorkHoursResumeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
