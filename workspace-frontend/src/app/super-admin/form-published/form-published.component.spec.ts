import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormPublishedComponent } from './form-published.component';

describe('FormPublishedComponent', () => {
  let component: FormPublishedComponent;
  let fixture: ComponentFixture<FormPublishedComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormPublishedComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormPublishedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
