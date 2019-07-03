import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormOverviewComponent } from './form-overview.component';

describe('FormOverviewComponent', () => {
  let component: FormOverviewComponent;
  let fixture: ComponentFixture<FormOverviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormOverviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormOverviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
