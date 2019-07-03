import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RequestPopComponent } from './request-pop.component';

describe('RequestPopComponent', () => {
  let component: RequestPopComponent;
  let fixture: ComponentFixture<RequestPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RequestPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RequestPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
