import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormAdminmodalNavComponent } from './form-adminmodal-nav.component';

describe('FormAdminmodalNavComponent', () => {
  let component: FormAdminmodalNavComponent;
  let fixture: ComponentFixture<FormAdminmodalNavComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormAdminmodalNavComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormAdminmodalNavComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
