import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskResponsibleComponent } from './task-responsible.component';

describe('TaskResponsibleComponent', () => {
  let component: TaskResponsibleComponent;
  let fixture: ComponentFixture<TaskResponsibleComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TaskResponsibleComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TaskResponsibleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
