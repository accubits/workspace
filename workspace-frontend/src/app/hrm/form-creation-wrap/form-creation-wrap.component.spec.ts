import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormCreationWrapComponent } from './form-creation-wrap.component';

describe('FormCreationWrapComponent', () => {
  let component: FormCreationWrapComponent;
  let fixture: ComponentFixture<FormCreationWrapComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormCreationWrapComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormCreationWrapComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
