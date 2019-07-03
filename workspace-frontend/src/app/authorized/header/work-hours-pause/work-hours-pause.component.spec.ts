import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkHoursPauseComponent } from './work-hours-pause.component';

describe('WorkHoursPauseComponent', () => {
  let component: WorkHoursPauseComponent;
  let fixture: ComponentFixture<WorkHoursPauseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WorkHoursPauseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WorkHoursPauseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
