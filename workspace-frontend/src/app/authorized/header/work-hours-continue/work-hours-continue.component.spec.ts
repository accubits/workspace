import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkHoursContinueComponent } from './work-hours-continue.component';

describe('WorkHoursContinueComponent', () => {
  let component: WorkHoursContinueComponent;
  let fixture: ComponentFixture<WorkHoursContinueComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WorkHoursContinueComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WorkHoursContinueComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
