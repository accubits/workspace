import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RenewPopComponent } from './renew-pop.component';

describe('RenewPopComponent', () => {
  let component: RenewPopComponent;
  let fixture: ComponentFixture<RenewPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RenewPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RenewPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
